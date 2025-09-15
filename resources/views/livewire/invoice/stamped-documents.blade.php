<?php

use App\Models\Invoice;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $feeType = '';
    public $startDate = '';
    public $endDate = '';

    public function with(): array
    {
        $query = Invoice::where('user_id', auth()->id())
                       ->where('status', 'stamped')
                       ->with(['user'])
                       ->orderBy('stamped_at', 'desc');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('rrr', 'like', '%' . $this->search . '%')
                  ->orWhere('amount', 'like', '%' . $this->search . '%');
            });
        }

        // Apply fee type filter
        if ($this->feeType) {
            $query->where('fee_type', $this->feeType);
        }

        // Apply date range filter
        if ($this->startDate) {
            $query->whereDate('stamped_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('stamped_at', '<=', $this->endDate);
        }

        return [
            'invoices' => $query->paginate(12),
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFeeType()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }
}; ?>

<main>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Stamped Documents') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg mb-4 sm:mb-6">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 sm:mb-6">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2 sm:gap-3">Stamped Documents</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and download your verified payment receipts</p>
                        </div>
                        <div class="flex items-center justify-center sm:justify-end">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium text-green-800 dark:text-green-200">{{ $invoices->total() }} Documents</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <input wire:model.live="search" type="text" placeholder="Search by RRR..."
                                class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-lg bg-white dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 focus:ring-2 focus:ring-green-500">
                        
                            <select wire:model.live="feeType" 
                                class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-lg bg-white dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 focus:ring-2 focus:ring-green-500">
                                <option value="">All Types</option>
                                <option value="school_fees">School Fees</option>
                                <option value="igr">IGR</option>
                            </select>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <input wire:model.live="startDate" type="date" 
                                    class="px-2 sm:px-3 py-2 text-xs sm:text-sm border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700 focus:ring-2 focus:ring-green-500">
                                
                                <input wire:model.live="endDate" type="date"
                                    class="px-2 sm:px-3 py-2 text-xs sm:text-sm border rounded-lg bg-white dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700 focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @forelse ($invoices as $invoice)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg sm:rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Document Preview -->
                        <div class="relative bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-4 sm:p-6">
                            @if($invoice->stamped_file)
                                <x-stamped-pdf-viewer :invoice-id="$invoice->id" :stamped-file="$invoice->stamped_file" />
                            @else
                                <div class="flex items-center justify-center h-24 sm:h-32">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-green-600 dark:text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xs sm:text-sm text-green-700 dark:text-green-300">No stamped file</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Document Info -->
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-zinc-900 dark:text-white text-base sm:text-lg mb-1 truncate">
                                        {{ ucfirst(str_replace('_', ' ', $invoice->fee_type)) }} Receipt
                                    </h3>
                                    <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 truncate">RRR: {{ $invoice->rrr }}</p>
                                </div>
                                <span class="px-2 sm:px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                    @if($invoice->stamped_file)
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else
                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @endif">
                                    @if($invoice->stamped_file)
                                        ✓ STAMPED
                                    @else
                                        PENDING
                                    @endif
                                </span>
                            </div>

                            <div class="space-y-2 sm:space-y-3 mb-3 sm:mb-4">
                                <div class="flex items-center text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="truncate">{{ ucfirst(str_replace('_', ' ', $invoice->fee_type)) }}</span>
                                </div>
                                <div class="flex items-center text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h6a2 2 0 012 2v4m-6 4v6m0-6l-3-3m3 3l3-3"></path>
                                    </svg>
                                    <span class="truncate">{{ $invoice->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                @if($invoice->stamped_file)
                                    <a href="{{ route('download.stamped.invoice', $invoice) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Receipt
                                    </a>
                                @else
                                    <div class="flex-1 inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-zinc-100 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400 text-xs sm:text-sm font-medium rounded-lg cursor-not-allowed">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Not Available
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-8 sm:py-12 px-4">
                            <svg class="w-16 h-16 sm:w-24 sm:h-24 text-zinc-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-white mb-2">No stamped documents found</h3>
                            <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-400 mb-4 sm:mb-6">
                                You don't have any stamped documents yet. Try adjusting your filters or upload a new invoice.
                            </p>
                            <a href="{{ route('invoice.upload') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Upload New Invoice
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($invoices->hasPages())
                <div class="mt-4 sm:mt-6 px-2 sm:px-0">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>

</main>
