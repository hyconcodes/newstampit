<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $rrr;
    public $fee_type;
    public $amount;
    public $invoice_file;

    public function save()
    {
        try {
            $this->validate(
                [
                    'rrr' => 'required|string|size:12',
                    'fee_type' => 'required|in:school_fees,igr',
                    'amount' => 'required|numeric',
                    'invoice_file' => 'required|mimes:pdf|max:2048',
                ],
                [
                    'rrr.size' => 'The RRR number must be exactly 12 digits.',
                ],
            );
            dd([
                'rrr' => $this->rrr,
                'fee_type' => $this->fee_type,
                'amount' => $this->amount,
                'invoice_file' => $this->invoice_file->store('invoices', 'public'),
            ]);

            session()->flash('success', 'Invoice uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload invoice: ' . $e->getMessage());
        }
    }
}; ?>

<div class="p-4">
    @include('includes.alert')

    <form wire:submit="save" class="space-y-4">
        <div>
            <label for="rrr" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">RRR Number</label>
            <flux:input type="text" wire:model="rrr" id="rrr"
                class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 shadow-sm dark:bg-zinc-700 dark:text-zinc-200" />
            @error('rrr')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

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

        <div>
            <label for="amount" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Amount</label>
            <flux:input type="number" wire:model="amount" id="amount"
                class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 shadow-sm dark:bg-zinc-700 dark:text-zinc-200" />
            @error('amount')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="invoice_file" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Invoice
                PDF</label>
            <flux:input type="file" wire:model="invoice_file" id="invoice_file" accept=".pdf"
                class="mt-1 block w-full dark:text-zinc-200" />
            @error('invoice_file')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
            <div class="text-sm text-green-500 text-center mt-4 dark:text-green-400">Only PDF files are allowed (max
                2MB)</div>
        </div>

        <div>
            <flux:button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md !text-white !bg-green-600 !hover:bg-green-700 !dark:bg-green-500 !dark:hover:bg-green-600 w-full focus:outline-none focus:ring-2 focus:ring-offset-2 !focus:ring-green-500 !dark:focus:ring-green-400">
                Upload Invoice
            </flux:button>
        </div>
    </form>
</div>
