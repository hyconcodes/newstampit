<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Stamp;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithFileUploads;

    public $igrStamp;
    public $schoolFeesStamp;
    public $uploadingType = null;
    public $newStampFile = null;
    public $uploadProgress = 0;
    public $isUploading = false;

    public function mount()
    {
        $this->loadStamps();
    }

    public function loadStamps()
    {
        $this->igrStamp = Stamp::where('type', 'igr')->first();
        $this->schoolFeesStamp = Stamp::where('type', 'school_fees')->first();
    }

    public function updatedNewStampFile()
    {
        if ($this->newStampFile) {
            $this->validate([
                'newStampFile' => 'required|image|mimes:png,jpg,jpeg|max:2048'
            ]);

            $this->isUploading = true;
            $this->uploadProgress = 0;

            // Simulate upload progress
            for ($i = 0; $i <= 100; $i += 10) {
                $this->uploadProgress = $i;
                $this->dispatch('progress-updated', $i);
                usleep(100000); // 0.1 second delay
            }

            $filename = $this->uploadingType . '_' . time() . '.' . $this->newStampFile->getClientOriginalExtension();
            $path = $this->newStampFile->storeAs('stamps', $filename);

            Stamp::updateOrCreate(
                ['type' => $this->uploadingType],
                [
                    'name' => ucfirst(str_replace('_', ' ', $this->uploadingType)) . ' Stamp',
                    'original_image' => $filename,
                    'uploaded_by' => auth()->id()
                ]
            );

            $this->isUploading = false;
            $this->uploadProgress = 100;
            $this->loadStamps();
            
            // Small delay to show 100% before closing
            usleep(500000); // 0.5 second delay
            
            $this->reset(['newStampFile', 'uploadingType', 'uploadProgress']);
            session()->flash('success', 'Stamp uploaded successfully!');
        }
    }

    public function uploadStamp($type)
    {
        // This method is now just for manual upload trigger if needed
        if (!$this->newStampFile) {
            session()->flash('error', 'Please select a file first.');
            return;
        }
        
        $this->updatedNewStampFile();
    }


    public function deleteStamp($type)
    {
        $stamp = Stamp::where('type', $type)->first();
        if ($stamp) {
            if ($stamp->original_image) Storage::delete($stamp->original_image);
            $stamp->delete();
            $this->loadStamps();
            session()->flash('success', 'Stamp deleted successfully!');
        }
    }

    public function startUpload($type)
    {
        $this->uploadingType = $type;
        $this->newStampFile = null;
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 px-2 py-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold bg-gradient-to-r from-green-600 to-green-800 dark:from-green-400 dark:to-green-600 bg-clip-text text-transparent mb-3 sm:mb-4">
                Stamp Management
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                {{-- Upload and manage IGR and School Fees stamps for invoice stamping --}}
                Upload and manage IGR stamps for remita stamping
            </p>
        </div>

        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6 sm:mb-8">
                <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- IGR Stamp -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        IGR Stamp
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($igrStamp && $igrStamp->image_url)
                        <div class="mb-4">
                            <img src="{{ asset('storage/app/public/stamps/' . $igrStamp->image_url) }}" alt="IGR Stamp" class="max-h-32 mx-auto border rounded-lg shadow-sm bg-gray-50">
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <button wire:click="startUpload('igr')" 
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-all duration-200">
                                Update Stamp
                            </button>
                            <button wire:click="deleteStamp('igr')" 
                                    wire:confirm="Are you sure you want to delete this stamp?"
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all duration-200">
                                Delete Stamp
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">No IGR stamp uploaded</p>
                            <button wire:click="startUpload('igr')" 
                                    class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-all duration-200">
                                Upload IGR Stamp
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- School Fees Stamp -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        School Fees Stamp
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($schoolFeesStamp && $schoolFeesStamp->image_url)
                        <div class="mb-4">
                            <img src="{{ asset('storage/app/public/stamps/' . $schoolFeesStamp->image_url) }}" alt="School Fees Stamp" class="max-h-32 mx-auto border rounded-lg shadow-sm bg-gray-50">
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <button wire:click="startUpload('school_fees')" 
                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-all duration-200">
                                Update Stamp
                            </button>
                            <button wire:click="deleteStamp('school_fees')" 
                                    wire:confirm="Are you sure you want to delete this stamp?"
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all duration-200">
                                Delete Stamp
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">No School Fees stamp uploaded</p>
                            <button wire:click="startUpload('school_fees')" 
                                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-all duration-200">
                                Upload School Fees Stamp
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        @if($uploadingType)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-semibold mb-4">Upload {{ ucfirst(str_replace('_', ' ', $uploadingType)) }} Stamp</h3>
                    
                    <div class="mb-4">
                        <input type="file" wire:model="newStampFile" accept="image/*" 
                               class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('newStampFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($isUploading)
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Uploading...</span>
                                <span>{{ $uploadProgress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $uploadProgress }}%"></div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex gap-3">
                        <button wire:click="uploadStamp('{{ $uploadingType }}')" 
                                {{ $isUploading ? 'disabled' : '' }}
                                class="flex-1 px-4 py-2 {{ $isUploading ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg font-medium transition-all duration-200">
                            {{ $isUploading ? 'Uploading...' : 'Upload' }}
                        </button>
                        <button wire:click="$set('uploadingType', null)" 
                                {{ $isUploading ? 'disabled' : '' }}
                                class="flex-1 px-4 py-2 {{ $isUploading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-500 hover:bg-gray-600' }} text-white rounded-lg font-medium transition-all duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
