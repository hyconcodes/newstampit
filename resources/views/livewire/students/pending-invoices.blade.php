<?php

use Livewire\Volt\Component;
use App\Models\Invoice;

new class extends Component {
    public $invoices;
    public $search = '';
    public $status = 'pending';

    public function mount()
    {
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        $this->invoices = auth()
            ->user()
            ->invoices()
            ->when($this->search, function ($query) {
                $query->where('rrr', 'like', '%' . $this->search . '%');
            })
            ->where('status', $this->status)
            ->latest()
            ->get();
    }

    public function updateStatus($invoiceId, $newStatus)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice && $invoice->user_id === auth()->id()) {
            $invoice->update(['status' => $newStatus]);
            $this->loadInvoices();
        }
    }
}; ?>

<div class="relative p-2 sm:p-2 lg:p-8 mb-20">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-neutral-950 dark:to-neutral-900">
            <!-- Floating Background Elements -->
            <x-floating-icons />
        </div>
    </div>
    <div class="max-w-7xl mx-auto mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-zinc-800 dark:text-zinc-200 mb-6">Your Invoices</h2>
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <input wire:model.live="search" type="text" placeholder="Search by invoice number..."
                class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
            <select wire:model.live="status"
                class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
                <option value="stamped">Stamped</option>
            </select>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @forelse ($invoices as $invoice)
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg overflow-hidden transform transition-all hover:scale-[1.02]">
                <div class="p-4 sm:p-6">
                    <!-- PDF Preview or Placeholder -->
                    <div class="">
                        @if ($invoice->invoice_file && Storage::exists($invoice->invoice_file))
                            <div id="pdf-viewer-{{ $invoice->id }}" class="mb-6 h-48 sm:h-56 flex items-center justify-center bg-zinc-100 dark:bg-zinc-700 rounded-lg overflow-hidden"></div>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
                            <script>
                                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';
                                
                                async function loadPDF{{ $invoice->id }}() {
                                    const url = "{{ asset('storage/' . $invoice->invoice_file) }}";
                                    const loadingTask = pdfjsLib.getDocument(url);
                                    
                                    try {
                                        const pdf = await loadingTask.promise;
                                        const page = await pdf.getPage(1);
                                        
                                        const scale = 1.5;
                                        const viewport = page.getViewport({ scale });
                                        
                                        const canvas = document.createElement('canvas');
                                        const context = canvas.getContext('2d');
                                        canvas.height = viewport.height;
                                        canvas.width = viewport.width;
                                        canvas.classList.add('rounded-lg');
                                        
                                        const renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        
                                        document.getElementById('pdf-viewer-{{ $invoice->id }}').appendChild(canvas);
                                        await page.render(renderContext);
                                    } catch (error) {
                                        console.error('Error loading PDF:', error);
                                    }
                                }
                                
                                loadPDF{{ $invoice->id }}();
                            </script>
                        @else
                            <div class="text-6xl text-green-500 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Invoice Details -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Invoice rrr #</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->rrr }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Amount</span>
                            <span
                                class="text-sm text-zinc-900 dark:text-zinc-100">₦{{ number_format($invoice->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Date</span>
                            <span
                                class="text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Status</span>
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $invoice->status === 'stamped'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : ($invoice->status === 'rejected'
                                        ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex flex-wrap gap-2 justify-end">
                        {{-- @if ($invoice->status === 'pending')
                            <button wire:click="updateStatus({{ $invoice->id }}, 'stamped')"
                                class="px-4 py-2 text-sm bg-green-500 text-white rounded-lg hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition-colors">
                                Mark as Stamped
                            </button>
                            <button wire:click="updateStatus({{ $invoice->id }}, 'rejected')"
                                class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 transition-colors">
                                Reject
                            </button>
                        @endif --}}
                        <a href="{{ route('invoice.download', $invoice) }}"
                            class="w-full px-4 py-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors">
                            Download
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-zinc-500 dark:text-zinc-400">
                No invoices found
            </div>
        @endforelse
    </div>
</div>
