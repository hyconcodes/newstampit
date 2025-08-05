<?php

use Livewire\Volt\Component;

new class extends Component {
    public $search = '';
    public $selectedFeeType = 'school_fees';
    public $showCancelModal = false;
    public $selectedInvoiceId = null;

    public function with(): array
    {
        return [
            'invoices' => \App\Models\Invoice::query()
                ->where('fee_type', 'school_fees')
                ->where('status', 'pending')
                ->when($this->search, function ($query) {
                    $query->where('rrr', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->get()
        ];
    }

    public function stamp($invoiceId)
    {
        $invoice = \App\Models\Invoice::findOrFail($invoiceId);
        $invoice->update(['status' => 'stamped']);
        
        $this->dispatch('invoice-stamped');
    }
}; ?>

<main>
    <div class="relative p-2 sm:p-2 lg:p-8 mb-20">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-neutral-950 dark:to-neutral-900">
                <x-floating-icons />
            </div>
        </div>

        <div class="max-w-7xl mx-auto mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-zinc-800 dark:text-zinc-200 mb-6">Pending School Fees Documents</h2>
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <input wire:model.live="search" type="text" placeholder="Search by RRR number..."
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
            </div>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse ($invoices as $invoice)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg overflow-hidden transform transition-all hover:scale-[1.02]">
                    <div class="p-4 sm:p-6">
                        <x-pdf-viewer :invoice-id="$invoice->id" :invoice-file="$invoice->invoice_file" buttonText="" />

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">RRR #</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->rrr }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Amount</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100">₦{{ number_format($invoice->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Date</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Status</span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Pending
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button wire:click="stamp({{ $invoice->id }})"
                                class="w-full px-4 py-2 text-sm bg-green-500 text-white rounded-lg hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition-colors">
                                Stamp Document
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-zinc-500 dark:text-zinc-400">
                    No pending school fees documents found
                </div>
            @endforelse
        </div>
    </div>
</main>
