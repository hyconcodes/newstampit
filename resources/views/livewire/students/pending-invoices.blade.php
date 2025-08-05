<?php

use Livewire\Volt\Component;
use App\Models\Invoice;

new class extends Component {
    public $invoices;
    public $search = '';
    public $showCancelModal = false;
    public $selectedInvoiceId;
    public $selectedFeeType = 'all'; // Added for fee type filtering

    public function mount()
    {
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        $query = auth()
            ->user()
            ->invoices()
            ->when($this->search, function ($query) {
                $query->where('rrr', 'like', '%' . $this->search . '%');
            })
            ->where('status', 'pending');

        if ($this->selectedFeeType !== 'all') {
            $query->where('fee_type', $this->selectedFeeType);
        }

        $this->invoices = $query->latest()->get();
    }

    public function updatedSelectedFeeType()
    {
        $this->loadInvoices();
    }

    public function confirmCancel($invoiceId)
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->showCancelModal = true;
    }

    public function cancelInvoice()
    {
        $invoice = Invoice::find($this->selectedInvoiceId);
        if ($invoice && $invoice->user_id === auth()->id()) {
            $invoice->update(['status' => 'cancelled']);
            $this->showCancelModal = false;
            $this->loadInvoices();
        }
    }
}; ?>
<main>
    <div class="relative p-2 sm:p-2 lg:p-8 mb-20">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-neutral-950 dark:to-neutral-900">
                <!-- Floating Background Elements -->
                <x-floating-icons />
            </div>
        </div>
        <div class="max-w-7xl mx-auto mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-zinc-800 dark:text-zinc-200 mb-6">Pending Invoices</h2>
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <input wire:model.live="search" type="text" placeholder="Search by invoice number..."
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                
                <!-- Fee Type Filter -->
                <div class="flex gap-2">
                    <button wire:click="$set('selectedFeeType', 'all')" 
                        class="px-4 py-2 rounded-lg {{ $selectedFeeType === 'all' ? 'bg-blue-500 text-white' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300' }}">
                        All
                    </button>
                    <button wire:click="$set('selectedFeeType', 'school_fees')"
                        class="px-4 py-2 rounded-lg {{ $selectedFeeType === 'school_fees' ? 'bg-blue-500 text-white' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300' }}">
                        School Fees
                    </button>
                    <button wire:click="$set('selectedFeeType', 'igr')"
                        class="px-4 py-2 rounded-lg {{ $selectedFeeType === 'igr' ? 'bg-blue-500 text-white' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300' }}">
                        IGR
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse ($invoices as $invoice)
                <div
                    class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg overflow-hidden transform transition-all hover:scale-[1.02]">
                    <div class="p-4 sm:p-6">
                        <!-- PDF Preview or Placeholder -->
                        <x-pdf-viewer :invoice-id="$invoice->id" :invoice-file="$invoice->invoice_file" buttonText="" />

                        <!-- Invoice Details -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Invoice rrr #</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->rrr }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Invoice type</span>
                                <span
                                    class="text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->fee_type === 'school_fees' ? 'School Fees Invoice' : 'IGR' }}</span>
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
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Pending
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('student.update-pending.invoices', $invoice->id) }}"
                                class="w-1/2 px-4 py-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors text-center">
                                Update
                            </a>
                            <button wire:click="confirmCancel({{ $invoice->id }})"
                                class="w-1/2 px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-zinc-500 dark:text-zinc-400">
                    No pending invoices found
                </div>
            @endforelse
        </div>

        <!-- Cancel Confirmation Modal -->
        @if ($showCancelModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 mb-4">Confirm Cancellation</h3>
                    <p class="text-zinc-600 dark:text-zinc-300 mb-6">Are you sure you want to cancel this invoice? This
                        action cannot be undone.</p>
                    <div class="flex justify-end gap-4">
                        <button wire:click="$set('showCancelModal', false)"
                            class="px-4 py-2 text-sm text-zinc-600 hover:text-zinc-800 dark:text-zinc-300 dark:hover:text-zinc-100">
                            No, Keep It
                        </button>
                        <button wire:click="cancelInvoice"
                            class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                            Yes, Cancel Invoice
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
