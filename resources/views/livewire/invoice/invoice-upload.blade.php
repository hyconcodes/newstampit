<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Invoice;

new class extends Component {
    use WithFileUploads;

    public $rrr;
    public $fee_type;
    public $invoice_file;
    public $extracted_numbers = [
        'remita' => null,
        'invoice' => null,
    ];

    public function extractNumbers($filePath)
    {
        try {
            // Read PDF file as raw text
            $content = file_get_contents($filePath);

            $remita = null;
            $invoice = null;

            // Match REMITA reference (e.g. REMITA-250819423315)
            if (preg_match('/REMITA-(\d+)/', $content, $matches)) {
                $remita = 'REMITA-' . $matches[1];
            }

            // Match 12-digit hyphenated number (xxxx-xxxx-xxxx)
            if (preg_match('/(\d{4}-\d{4}-\d{4})/', $content, $matches)) {
                $invoice = $matches[1];
            }

            return [
                'remita' => $remita,
                'invoice' => $invoice,
            ];
        } catch (\Exception $e) {
            return [
                'remita' => null,
                'invoice' => null,
            ];
        }
    }

    public function save()
    {
        try {
            // Different validation rules based on fee type
            $validationRules = [
                'fee_type' => 'required|in:school_fees,igr',
                'invoice_file' => 'required|mimes:pdf|max:2048',
                'rrr' => 'required|string|size:12|unique:invoices,rrr',
            ];

            $validationMessages = [
                'invoice_file.mimes' => 'The invoice file must be a PDF file.',
                'invoice_file.max' => 'The invoice file must be less than 2MB.',
                'rrr.size' => 'The RRR number must be exactly 12 digits.',
                'rrr.unique' => 'Used RRR number.',
                'rrr.required' => "Unable to get the RRR number, it's required and must be valid.",
            ];

            $this->validate($validationRules, $validationMessages);

            $invoice_path = $this->invoice_file->store('invoices', 'public');
            $extractedData = $this->extractNumbers(storage_path('app/public/' . $invoice_path));

            if ($this->fee_type === 'igr') {

                // Remove hyphens from extracted number for comparison
                $extractedRrr = str_replace('-', '', $extractedData['invoice']);

                // if ($extractedRrr !== $this->rrr) {
                //     throw new \Exception('The RRR number entered does not match the one in the PDF document.');
                // }
            } elseif ($this->fee_type === 'school_fees') {

                // Extract digits from REMITA reference (e.g., REMITA-250819423315 -> 250819423315)
                $extractedRrr = preg_replace('/[^0-9]/', '', $extractedData['remita']);

                // if ($extractedRrr !== $this->rrr) {
                //     throw new \Exception('The RRR number entered does not match the REMITA reference in the PDF document.');
                // }
            }

            Invoice::create([
                'user_id' => auth()->id(),
                'rrr' => $this->rrr,
                'fee_type' => $this->fee_type,
                'invoice_file' => $invoice_path,
            ]);

            $this->reset(['rrr', 'fee_type', 'invoice_file']);
            session()->flash('success', 'Invoice uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload invoice: ' . $e->getMessage());
        }
    }
}; ?>

<div class="p-4 relative">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-neutral-950 dark:to-neutral-900">
            <x-floating-icons />
        </div>
    </div>

    @include('includes.alert')

    <form wire:submit="save" class="space-y-4">
        <div>
            <label for="fee_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Fee Type</label>
            <flux:select wire:model="fee_type" id="fee_type"
                class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 shadow-sm dark:bg-zinc-700 dark:text-zinc-200">
                <option value="">Select Fee Type</option>
                <option value="school_fees">School Fees</option>
                <option value="igr">IGR</option>
            </flux:select>
            @error('fee_type')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- RRR input (now always visible) --}}
        <div>
            <label for="rrr" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">RRR Number</label>
            <flux:input type="text" wire:model="rrr" id="rrr"
                class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 shadow-sm dark:bg-zinc-700 dark:text-zinc-200"
                placeholder="12-digit RRR number" disabled />
            @error('rrr')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="invoice_file" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Invoice
                PDF</label>

            <!-- Drag and Drop Zone -->
            <div id="drop-zone"
                class="mt-1 flex justify-center px-6 py-8 border-2 border-dashed rounded-lg border-zinc-300 dark:border-zinc-600 transition-all duration-200 cursor-pointer hover:border-green-500 hover:bg-green-50 dark:hover:border-green-400 dark:hover:bg-green-900/20 relative group">

                <div class="space-y-2 text-center">
                    <svg class="mx-auto h-12 w-12 text-zinc-400 group-hover:text-green-500 transition-colors"
                        stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <div class="text-center">
                        <p id="drop-text" class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">
                            @if ($invoice_file)
                                {{ $invoice_file->getClientOriginalName() }}
                            @else
                                Drag and drop your PDF here or click to select
                            @endif
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">PDF up to 2MB</p>
                    </div>
                </div>

                <!-- Hidden file input -->
                <input type="file" wire:model="invoice_file" id="invoice_file" accept=".pdf"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />

                <!-- Drag overlay -->
                <div id="drag-overlay"
                    class="absolute inset-0 bg-green-500 bg-opacity-10 border-2 border-green-500 rounded-lg flex items-center justify-center opacity-0 transition-opacity duration-200 pointer-events-none">
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-green-500 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">Drop your PDF here</p>
                    </div>
                </div>
            </div>

            <!-- Show upload progress -->
            <div wire:loading wire:target="invoice_file" class="mt-3">
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-blue-600 h-2.5 rounded-full dark:bg-blue-500 animate-pulse" style="width: 100%">
                    </div>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 text-center">Uploading and processing PDF...</p>
            </div>

            <!-- Show selected file info -->
            @if ($invoice_file)
                <div
                    class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-green-800 dark:text-green-200 font-medium">
                                {{ $invoice_file->getClientOriginalName() }}
                            </p>
                            <p class="text-xs text-green-600 dark:text-green-400">
                                {{ number_format($invoice_file->getSize() / 1024, 1) }} KB
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @error('invoice_file')
                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-md border border-red-200 dark:border-red-800">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-800 dark:text-red-200 text-sm">{{ $message }}</p>
                    </div>
                </div>
            @enderror
        </div>

        <div>
            <flux:button type="submit" wire:loading.attr="disabled" wire:target="save"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md !text-white !bg-green-600 !hover:bg-green-700 !dark:bg-green-500 !dark:hover:bg-green-600 w-full focus:outline-none focus:ring-2 focus:ring-offset-2 !focus:ring-green-500 !dark:focus:ring-green-400 disabled:opacity-50">

                <span wire:loading.remove wire:target="save">Upload Invoice</span>
                <span wire:loading wire:target="save">Processing...</span>
            </flux:button>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
    // Set PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('invoice_file');
        const dragOverlay = document.getElementById('drag-overlay');
        const dropText = document.getElementById('drop-text');

        if (!dropZone || !fileInput) return;

        let dragCounter = 0;

        // Prevent default drag behaviors on the entire document
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            document.addEventListener(eventName, preventDefaults, false);
        });

        // Handle drag events on the drop zone
        dropZone.addEventListener('dragenter', handleDragEnter, false);
        dropZone.addEventListener('dragover', handleDragOver, false);
        dropZone.addEventListener('dragleave', handleDragLeave, false);
        dropZone.addEventListener('drop', handleDrop, false);

        // Handle click to open file dialog
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Handle file selection via input
        fileInput.addEventListener('change', handleFileChange, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function handleDragEnter(e) {
            preventDefaults(e);
            dragCounter++;
            showDragOverlay();
        }

        function handleDragOver(e) {
            preventDefaults(e);
            e.dataTransfer.dropEffect = 'copy';
            showDragOverlay();
        }

        function handleDragLeave(e) {
            preventDefaults(e);
            dragCounter--;
            if (dragCounter === 0) {
                hideDragOverlay();
            }
        }

        function handleDrop(e) {
            preventDefaults(e);
            dragCounter = 0;
            hideDragOverlay();

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                handleFileSelection(file);
            }
        }

        function handleFileChange(e) {
            const files = e.target.files;
            if (files.length > 0) {
                const file = files[0];
                updateDropText(file.name);
                extractPdfNumbers(file);
            }
        }

        function handleFileSelection(file) {
            // Validate file type and size
            if (file.type !== 'application/pdf') {
                showError('Please select a PDF file only.');
                return;
            }

            if (file.size > 2 * 1024 * 1024) { // 2MB
                showError('File size must be less than 2MB.');
                return;
            }

            // Create a new DataTransfer object to properly set the file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            // Set the files on the input element
            fileInput.files = dataTransfer.files;

            // Trigger the change event to notify Livewire
            fileInput.dispatchEvent(new Event('change', {
                bubbles: true
            }));

            // Update UI and extract numbers
            updateDropText(file.name);
            extractPdfNumbers(file);
        }

        async function extractPdfNumbers(file) {
            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;

                let fullText = '';

                // Extract text from all pages
                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);
                    const textContent = await page.getTextContent();
                    const pageText = textContent.items.map(item => item.str).join(' ');
                    fullText += pageText + ' ';
                }

                // Extract numbers based on patterns
                let remita = null;
                let invoice = null;

                // Match REMITA reference (e.g. REMITA-250819423315)
                const remitaMatch = fullText.match(/REMITA-\d+/);
                if (remitaMatch) {
                    remita = remitaMatch[0];
                }

                // Match 12-digit hyphenated number (xxxx-xxxx-xxxx)
                const invoiceMatch = fullText.match(/\d{4}-\d{4}-\d{4}/);
                if (invoiceMatch) {
                    invoice = invoiceMatch[0];
                }

                // Decide what to fill into Livewire's `rrr`
                if (remita) {
                    // strip REMITA- and set RRR
                    @this.set('rrr', remita.replace('REMITA-', ''));
                } else if (invoice) {
                    // remove hyphens and set RRR
                    @this.set('rrr', invoice.replace(/-/g, ''));
                }

                console.log('Extracted:', {
                    remita,
                    invoice
                });

            } catch (error) {
                console.error('Error extracting PDF numbers:', error);
                showError('Failed to extract numbers from PDF. Please try again.');
            }
        }


        function showDragOverlay() {
            if (dragOverlay) {
                dragOverlay.style.opacity = '1';
            }
        }

        function hideDragOverlay() {
            if (dragOverlay) {
                dragOverlay.style.opacity = '0';
            }
        }

        function updateDropText(fileName) {
            if (dropText) {
                dropText.textContent = fileName;
            }
        }

        function showError(message) {
            // You can customize this to show errors in your preferred way
            const errorDiv = document.createElement('div');
            errorDiv.className =
                'mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-md border border-red-200 dark:border-red-800';
            errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-red-800 dark:text-red-200 text-sm">${message}</p>
            </div>
        `;

            // Remove any existing error messages
            const existingError = dropZone.parentNode.querySelector('.bg-red-50, .dark\\:bg-red-900\\/20');
            if (existingError && !existingError.querySelector('[wire\\:key]')) {
                existingError.remove();
            }

            dropZone.parentNode.insertBefore(errorDiv, dropZone.nextSibling);

            // Remove error after 5 seconds
            setTimeout(() => {
                if (errorDiv && errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 5000);
        }

        // Listen for Livewire updates to update the drop text
        document.addEventListener('livewire:updated', function() {
            const newFileInput = document.getElementById('invoice_file');
            const newDropText = document.getElementById('drop-text');

            if (newFileInput && newFileInput.files.length === 0 && newDropText) {
                newDropText.textContent = 'Drag and drop your PDF here or click to select';
            }
        });
    });
</script>
