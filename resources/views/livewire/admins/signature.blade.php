<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

new #[Layout('components.layouts.app')] class extends Component
{
    public $signature = '';
    public $hasExistingSignature = false;

    public function mount()
    {
        $this->hasExistingSignature = !empty(auth()->user()->signature);
    }

    public function saveSignature()
    {
        $this->validate([
            'signature' => 'required|string'
        ]);

        // Convert base64 signature to image file
        $signatureData = $this->signature;
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);
        $signatureImage = base64_decode($signatureData);

        // Generate unique filename
        $filename = 'signatures/' . auth()->id() . '_' . time() . '.png';
        
        // Store the signature
        Storage::disk('public')->put($filename, $signatureImage);

        // Update user's signature path
        auth()->user()->update([
            'signature' => $filename
        ]);

        $this->hasExistingSignature = true;
        
        session()->flash('success', 'Signature saved successfully!');
        $this->signature = '';
    }

    public function clearSignature()
    {
        // Delete existing signature file if exists
        if (auth()->user()->signature) {
            Storage::disk('public')->delete(auth()->user()->signature);
        }

        // Clear signature from database
        auth()->user()->update([
            'signature' => null
        ]);

        $this->hasExistingSignature = false;
        session()->flash('success', 'Signature cleared successfully!');
    }

    public function with(): array
    {
        return [
            'user' => auth()->user()
        ];
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 px-2 py-4 sm:px-6 lg:px-8 shadow-2xs rounded-2xl">
    <!-- Header Section -->
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8 sm:mb-12">
            {{-- <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-full mb-4 sm:mb-6 shadow-lg">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </div> --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold bg-gradient-to-r from-green-600 to-green-800 dark:from-green-400 dark:to-green-600 bg-clip-text text-transparent mb-3 sm:mb-4">
                Digital Signature
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Create and manage your professional digital signature for invoice stamping
            </p>
        </div>

        <!-- Success Message -->
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

        <!-- Main Content Grid -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- Current Signature Section -->
            @if($hasExistingSignature && $user->signature)
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Current Signature
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-4 border-2 border-dashed border-gray-200 dark:border-gray-600">
                                <img src="{{ asset('storage/' . $user->signature) }}" 
                                     alt="Current Signature" 
                                     class="max-h-32 w-full object-contain bg-white rounded-lg shadow-sm">
                            </div>
                            <button wire:click="clearSignature" 
                                    wire:confirm="Are you sure you want to clear your signature?"
                                    class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-red-500/20 shadow-lg">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Clear Signature
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Signature Creation Section -->
            <div class="{{ $hasExistingSignature && $user->signature ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            {{ $hasExistingSignature ? 'Update Signature' : 'Create Digital Signature' }}
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <!-- Canvas Container -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-4 sm:p-6 mb-6 border-2 border-dashed border-green-300 dark:border-green-600">
                            <div class="flex flex-col items-center">
                                <canvas id="signature-pad" 
                                        class="border-2 border-green-200 dark:border-green-700 rounded-xl bg-white shadow-inner cursor-crosshair mx-auto"
                                        width="300" 
                                        height="300"
                                        style="max-width: 300px; max-height: 300px;">
                                </canvas>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 text-center">
                                    Draw your signature above using mouse or touch
                                </p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button id="clear-signature" 
                                    class="flex-1 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Canvas
                            </button>
                            <button id="save-signature" 
                                    wire:click="saveSignature"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-500/20 shadow-lg">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $hasExistingSignature ? 'Update Signature' : 'Save Signature' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="max-w-4xl mx-auto mt-8">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 dark:bg-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
                            How to Create Your Digital Signature
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-green-700 dark:text-green-300">
                            <div class="flex items-start gap-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                                <span>Use your mouse or touch device to draw your signature in the canvas</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                                <span>Your signature will be used to digitally stamp approved invoices</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                                <span>Make sure your signature is clear and professional</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">4</span>
                                <span>You can update your signature anytime by creating a new one</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');
            const ctx = canvas.getContext('2d');
            let isDrawing = false;
            let lastX = 0;
            let lastY = 0;

            // Make canvas responsive
            function resizeCanvas() {
                const container = canvas.parentElement;
                const containerWidth = container.clientWidth - 32; // Account for padding
                const aspectRatio = 1; // 1:1 ratio for square/cube shape
                
                let canvasWidth = Math.min(containerWidth, 300); // Reduced max width to 300px
                let canvasHeight = canvasWidth / aspectRatio;
                
                // Set display size
                canvas.style.width = canvasWidth + 'px';
                canvas.style.height = canvasHeight + 'px';
                
                // Set actual canvas size (no scaling for simplicity)
                canvas.width = canvasWidth;
                canvas.height = canvasHeight;
                
                // Clear canvas (transparent background)
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                // Drawing settings
                ctx.strokeStyle = '#1e40af'; // Blue pen color
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
            }

            // Initial canvas setup
            resizeCanvas();
            
            // Resize canvas on window resize
            window.addEventListener('resize', resizeCanvas);

            function getEventPos(e) {
                const rect = canvas.getBoundingClientRect();
                
                let clientX, clientY;
                
                if (e.touches && e.touches[0]) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                } else {
                    clientX = e.clientX;
                    clientY = e.clientY;
                }
                
                // Calculate the position relative to the canvas with proper scaling
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function startDrawing(e) {
                isDrawing = true;
                const pos = getEventPos(e);
                lastX = pos.x;
                lastY = pos.y;
            }

            function draw(e) {
                if (!isDrawing) return;
                
                const pos = getEventPos(e);
                
                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();

                lastX = pos.x;
                lastY = pos.y;
            }

            function stopDrawing() {
                isDrawing = false;
            }

            // Mouse events
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events for mobile
            canvas.addEventListener('touchstart', function(e) {
                e.preventDefault();
                startDrawing(e);
            });

            canvas.addEventListener('touchmove', function(e) {
                e.preventDefault();
                draw(e);
            });

            canvas.addEventListener('touchend', function(e) {
                e.preventDefault();
                stopDrawing();
            });

            // Prevent scrolling when touching the canvas
            document.body.addEventListener('touchstart', function(e) {
                if (e.target == canvas) {
                    e.preventDefault();
                }
            }, { passive: false });

            document.body.addEventListener('touchend', function(e) {
                if (e.target == canvas) {
                    e.preventDefault();
                }
            }, { passive: false });

            document.body.addEventListener('touchmove', function(e) {
                if (e.target == canvas) {
                    e.preventDefault();
                }
            }, { passive: false });

            // Clear canvas button
            document.getElementById('clear-signature').addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });

            // Save signature button
            document.getElementById('save-signature').addEventListener('click', function() {
                const signatureData = canvas.toDataURL('image/png');
                @this.set('signature', signatureData);
            });
        });
    </script>
</div>
