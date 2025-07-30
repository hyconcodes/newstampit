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
        $this->invoices = auth()->user()
            ->invoices()
            ->when($this->search, function($query) {
                $query->where('invoice_number', 'like', '%' . $this->search . '%');
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

<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Your Invoices</h2>
        <div class="mt-4 flex gap-4">
            <input 
                wire:model.live="search" 
                type="text" 
                placeholder="Search by invoice number..."
                class="px-4 py-2 border rounded-lg"
            >
            <select 
                wire:model.live="status" 
                class="px-4 py-2 border rounded-lg"
            >
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($invoices as $invoice)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($invoice->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($invoice->status === 'pending')
                                <button 
                                    wire:click="updateStatus({{ $invoice->id }}, 'paid')"
                                    class="text-green-600 hover:text-green-900 mr-2"
                                >
                                    Mark as Paid
                                </button>
                                <button 
                                    wire:click="updateStatus({{ $invoice->id }}, 'cancelled')"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Cancel
                                </button>
                            @endif
                            <a 
                                href="{{ route('invoice.download', $invoice) }}" 
                                class="text-blue-600 hover:text-blue-900 ml-2"
                            >
                                Download
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No invoices found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
