<?php

namespace App\Services;

use App\Models\Stamp;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PdfStampingService
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function stampPdf($pdfPath, $adminId)
    {
        $admin = User::findOrFail($adminId);
        
        // Check if PDF is encrypted before processing
        if ($this->isPdfEncrypted($pdfPath)) {
            throw new \Exception('Cannot stamp encrypted/password-protected PDF documents. Please upload an unencrypted PDF file.');
        }
        
        // Determine stamp type based on admin permissions
        $stampType = $this->getStampType($admin);
        
        // Get the appropriate stamp
        $stamp = Stamp::where('type', $stampType)->first();
        
        if (!$stamp || !$stamp->original_image) {
            throw new \Exception("No {$stampType} stamp found. Please upload a stamp image in the admin panel before attempting to stamp documents.");
        }

        // Check if admin has signature before creating composite stamp
        if (!$admin->signature) {
            throw new \Exception("No digital signature found for admin. Please create a digital signature in the admin panel before attempting to stamp documents.");
        }

        // Create composite stamp with timestamp and signature
        $compositeStampPath = $this->createCompositeStamp($stamp, $admin);
        
        // Apply stamp to PDF
        $stampedPdfPath = $this->applyStampToPdf($pdfPath, $compositeStampPath);
        
        // Clean up temporary composite stamp
        if (file_exists($compositeStampPath)) {
            unlink($compositeStampPath);
        }
        
        return $stampedPdfPath;
    }

    protected function getStampType($admin)
    {
        if ($admin->hasPermissionTo('stamp.igr.invoices')) {
            return 'igr';
        } elseif ($admin->hasPermissionTo('stamp.school.fees.invoices')) {
            return 'school_fees';
        }
        
        throw new \Exception('Admin does not have stamping permissions');
    }

    protected function isPdfEncrypted($pdfPath)
    {
        try {
            $content = file_get_contents($pdfPath);
            
            // Check for encryption indicators in PDF content
            if (strpos($content, '/Encrypt') !== false) {
                return true;
            }
            
            // Try to create a temporary FPDI instance to test
            $testPdf = new Fpdi();
            $testPdf->setSourceFile($pdfPath);
            
            return false;
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'encrypted') !== false) {
                return true;
            }
            // If it's another error, let it pass through for now
            return false;
        }
    }

    protected function createCompositeStamp($stamp, $admin)
    {
        $stampPath = storage_path('app/public/stamps/' . $stamp->original_image);
        
        if (!file_exists($stampPath)) {
            throw new \Exception('Stamp image not found at: ' . $stampPath);
        }

        // Load the stamp image
        $stampImage = $this->imageManager->read($stampPath);
        
        // Get stamp dimensions
        $stampWidth = $stampImage->width();
        $stampHeight = $stampImage->height();
        
        // Create timestamp as a separate image for maximum control
        $timestamp = now()->format('d M Y');
        
        // Calculate dimensions for timestamp image (make it large)
        $timestampWidth = $stampWidth * 0.8; // 80% of stamp width
        $timestampHeight = $stampHeight * 0.3; // 30% of stamp height
        
        // Create a new blank image for the timestamp
        $timestampImage = $this->imageManager->create($timestampWidth, $timestampHeight);
        
        // Calculate much larger font size like HTML h1 tag (typically 2em or 32px equivalent)
        // Making it proportionally much larger - like h1 which is typically 200% of normal text
        $fontSize = min($timestampWidth * 0.4, $timestampHeight * 1.2); // Significantly increased for h1-like size
        
        // Add the timestamp text to the dedicated image
        $timestampImage->text($timestamp, $timestampWidth / 2, $timestampHeight / 2, function ($font) use ($fontSize) {
            $font->size($fontSize);
            $font->color('#000080'); // Dark blue
            $font->align('center');
            $font->valign('middle');
        });
        
        // Place the timestamp image on the stamp
        $timestampX = ($stampWidth - $timestampWidth) / 2;
        $timestampY = ($stampHeight - $timestampHeight) / 2 - 50;
        $stampImage->place($timestampImage, 'top-left', $timestampX, $timestampY);

        // Add admin signature if available - debug and fix placement
        if ($admin->signature) {
            // The signature field contains the full filename, so construct the correct path
            $signaturePath = storage_path('app/public/signatures/' . basename($admin->signature));
            
            if (file_exists($signaturePath)) {
                $signatureImage = $this->imageManager->read($signaturePath);
                
                // Make signature much larger and more visible
                $signatureWidth = min(300, $stampWidth * 0.9);
                $signatureHeight = min(150, $stampHeight * 0.5);
                $signatureImage->resize($signatureWidth, $signatureHeight);
                
                // Position signature in the center of the stamp
                $stampImage->place($signatureImage, 'center', 0, 0);
            } else {
                throw new \Exception('Digital signature file not found. Please recreate your digital signature in the admin panel.');
            }
        }

        // Save composite stamp to temporary location
        $tempStampPath = storage_path('app/temp/composite_stamp_' . time() . '.png');
        
        // Ensure temp directory exists
        if (!is_dir(dirname($tempStampPath))) {
            mkdir(dirname($tempStampPath), 0755, true);
        }
        
        $stampImage->save($tempStampPath);
        
        return $tempStampPath;
    }

    protected function applyStampToPdf($pdfPath, $stampImagePath)
    {
        $pdf = new Fpdi();
        
        try {
            // Import the existing PDF
            $pageCount = $pdf->setSourceFile($pdfPath);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'encrypted') !== false) {
                throw new \Exception('Cannot stamp encrypted/password-protected PDF documents. Please provide an unencrypted PDF.');
            }
            throw $e;
        }
        
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Import a page
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            
            // Add a page with the same orientation and size
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            
            // Use the imported page as template
            $pdf->useTemplate($templateId);
            
            // Add stamp to center of the page (only on first page for receipts)
            if ($pageNo === 1) {
                $stampWidth = 60; // mm
                $stampHeight = 60; // mm
                
                $x = ($size['width'] - $stampWidth) / 2;
                $y = ($size['height'] - $stampHeight) / 2;
                
                $pdf->Image($stampImagePath, $x, $y, $stampWidth, $stampHeight, 'PNG');
            }
        }
        
        // Generate new filename for stamped PDF
        $pathInfo = pathinfo($pdfPath);
        $stampedPdfPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_stamped.' . $pathInfo['extension'];
        
        // Save the stamped PDF
        $pdf->Output('F', $stampedPdfPath);
        
        return $stampedPdfPath;
    }
}
