<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Invoice;

new class extends Component {
    use WithFileUploads;
    
    public $invoice;
    public $rrr;
    public $fee_type;
    public $amount;
    public $invoice_file;

    public function mount($id) {
        $this->invoice = Invoice::findOrFail($id);
        $this->rrr = $this->invoice->rrr;
        $this->fee_type = $this->invoice->fee_type;
        $this->amount = $this->invoice->amount;
        $this->invoice_file = null;
    }

    public function updateInvoice() {
        $this->validate([
            'rrr' => 'required|string',
            'fee_type' => 'required|in:school_fees,igr',
            // 'amount' => 'required|numeric|min:0',
            'invoice_file' => 'nullable|file|mimes:pdf'
        ]);

        $updateData = [
            'rrr' => $this->rrr,
            'fee_type' => $this->fee_type,
            // 'amount' => $this->amount,
        ];

        if ($this->invoice_file) {
            $invoice_file_path = $this->invoice_file->store('invoices', 'public');
            $updateData['invoice_file'] = $invoice_file_path;
        }

        $this->invoice->update($updateData);

        session()->flash('success', 'Invoice updated successfully.');
    }
}; ?>
<main>
    <div class="relative p-2 sm:p-2 lg:p-8 mb-20">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-neutral-950 dark:to-neutral-900">
                <!-- Background Gradient -->
            </div>
        </div>
    @include('includes.alert')

        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-zinc-800 dark:text-zinc-200 mb-6">Update Invoice</h2>

            {{-- @if (session()->has('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif --}}

            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg p-6">
                <form wire:submit.prevent="updateInvoice">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-300 mb-2" for="rrr">
                                RRR Number
                            </label>
                            <input wire:model="rrr" type="text" 
                                class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                            @error('rrr') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-300 mb-2" for="fee_type">
                                Fee Type
                            </label>
                            <select wire:model="fee_type" 
                                class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                                <option value="school_fees">School Fee</option>
                                <option value="igr">IGR</option>
                            </select>
                            @error('fee_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-300 mb-2" for="amount">
                                Amount
                            </label>
                            <input wire:model="amount" type="number" step="0.01"
                                class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div> --}}

                        <div>
                            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-300 mb-2" for="invoice_file">
                                Invoice File
                            </label>
                            <input wire:model="invoice_file" type="file" accept=".pdf"
                                class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700">
                            @error('invoice_file') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-4">
                            <flux:button type="submit" 
                                class="w-full px-6 py-2 !bg-green-500 !text-white rounded-lg !hover:bg-green-600 !dark:bg-green-600 !dark:hover:bg-green-700 transition-colors">
                                Update Invoice
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
