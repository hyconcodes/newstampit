<?php

use Livewire\Volt\Component;
use App\Services\PdfStampingService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceStampedMail;

new class extends Component {
    public $search = '';
    public $selectedFeeType = 'school_fees';
    public $showCancelModal = false;
    public $selectedInvoiceId = null;
    public $statusFilter = '';
    public $startDate = '';
    public $endDate = '';

    public function with(): array
    {
        return [
            'invoices' => \App\Models\Invoice::query()
                ->where('fee_type', 'igr')
                ->when($this->statusFilter, function($query) {
                    $query->where('status', $this->statusFilter);
                }, function($query) {
                    $query->whereIn('status', ['pending', 'stamped']);
                })
                ->when($this->search, function ($query) {
                    $query->where('rrr', 'like', '%' . $this->search . '%');
                })
                ->when($this->startDate && $this->endDate, function($query) {
                    $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                })
                ->latest()
                ->get(),
        ];
    }

    public function stamp($invoiceId)
    {
        try {
            $invoice = \App\Models\Invoice::findOrFail($invoiceId);
            
            // Get the original PDF path
            $originalPdfPath = storage_path('app/public/' . $invoice->invoice_file);
            
            if (!file_exists($originalPdfPath)) {
                session()->flash('error', 'Invoice file not found');
                return;
            }

            // Create stamping service instance
            $stampingService = new PdfStampingService();
            
            // Apply digital stamp to PDF
            $stampedPdfPath = $stampingService->stampPdf($originalPdfPath, auth()->id());
            
            // Generate new filename for stamped version
            $pathInfo = pathinfo($invoice->invoice_file);
            $stampedFileName = $pathInfo['filename'] . '_stamped.' . $pathInfo['extension'];
            
            // Move stamped PDF to stamped invoices directory
            $finalStampedPath = 'stamped-invoices/' . $stampedFileName;
            Storage::disk('public')->put($finalStampedPath, file_get_contents($stampedPdfPath));
            
            // Update invoice with stamped file and status
            $invoice->update([
                'status' => 'stamped',
                'stamped_file' => $finalStampedPath,
                'stamped_by' => auth()->id(),
                'stamped_at' => now()
            ]);

            // Clean up temporary stamped file
            if (file_exists($stampedPdfPath)) {
                unlink($stampedPdfPath);
            }

            // Send email notification to student
            try {
                Mail::to($invoice->user->email)->send(new InvoiceStampedMail($invoice));
            } catch (\Exception $emailError) {
                // Log email error but don't fail the stamping process
                \Log::error('Failed to send stamped invoice email: ' . $emailError->getMessage());
            }

            $this->dispatch('invoice-stamped');
            session()->flash('success', 'Invoice stamped successfully and notification sent to student!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error stamping invoice: ' . $e->getMessage());
        }
    }
}; ?>

<main>
    <div class="relative p-2 sm:p-2 lg:p-2 mb-20">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-neutral-950 dark:to-neutral-900">
                {{-- <x-floating-icons /> --}}
            </div>
        </div>
        @include('includes.alert')

        <div class="max-w-7xl mx-auto mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-zinc-800 dark:text-zinc-200 mb-6">School Fees
                Documents</h2>
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <input wire:model.live="search" type="text" placeholder="Search by RRR number..."
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                
                <select wire:model.live="statusFilter"
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="stamped">Stamped</option>
                </select>

                <input wire:model.live="startDate" type="date" 
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                
                <input wire:model.live="endDate" type="date"
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
            </div>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @forelse ($invoices as $invoice)
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
                    <x-admin-pdf-viewer :invoice-id="$invoice->id" :invoice-file="$invoice->invoice_file" :status="$invoice->status" buttonText="" />

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-300">RRR #</span>
                            <span class="text-zinc-900 dark:text-zinc-100">{{ $invoice->rrr }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-300">Date</span>
                            <span
                                class="text-zinc-900 dark:text-zinc-100">{{ $invoice->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-zinc-600 dark:text-zinc-300">Status</span>
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>

                    @if($invoice->status === 'pending')
                        <flux:button wire:click="stamp({{ $invoice->id }})"
                            class="w-full mt-4 px-3 py-2 text-sm !bg-green-500 !text-white rounded !hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                            Stamp Document
                        </flux:button>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-zinc-500 dark:text-zinc-400">
                    No igr documents found
                </div>
            @endforelse
        </div>
    </div>
</main>
