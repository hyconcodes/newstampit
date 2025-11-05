@props(['invoiceId', 'invoiceFile', 'buttonText' => 'View PDF', 'status'])

@php
    $modalId = 'pdf-modal-' . $invoiceId;
    $viewerId = 'pdf-viewer-' . $invoiceId;
    $previewId = 'pdf-preview-' . $invoiceId;
@endphp

<div class="contents">
    @if ($invoiceFile && Storage::exists($invoiceFile))
        <div class="relative group cursor-pointer">
            <!-- Main Icon -->
            <div class="w-full h-full mx-auto" onclick="document.getElementById('{{ $modalId }}').showModal(); loadPDF{{ $invoiceId }}(false);">
                @if($status === 'stamped')
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-full w-full p-4 rounded-lg transform transition-all duration-300 group-hover:scale-105 group-hover:shadow-lg" 
                        viewBox="0 0 200 200">
                        <defs>
                            <linearGradient id="gradient-stamp-{{ $invoiceId }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color: #10B981" /> <!-- Green -->
                                <stop offset="100%" style="stop-color: #F59E0B" /> <!-- Gold -->
                            </linearGradient>
                        </defs>
                        <!-- Outer circle -->
                        <circle cx="100" cy="100" r="90" fill="none" stroke="url(#gradient-stamp-{{ $invoiceId }})" stroke-width="5" />
                        
                        <!-- Inner circle with text path -->
                        <circle cx="100" cy="100" r="75" fill="none" stroke="url(#gradient-stamp-{{ $invoiceId }})" stroke-width="2" />
                        
                        <!-- Circular text on top -->
                        <path id="top-circle-{{ $invoiceId }}" d="M100,25 A75,75 0 0,1 175,100" fill="none" />
                        <text>
                            <textPath href="#top-circle-{{ $invoiceId }}" startOffset="50%" text-anchor="middle" fill="url(#gradient-stamp-{{ $invoiceId }})" font-weight="bold" font-size="14">BOUESTI</textPath>
                        </text>
                        
                        <!-- Circular text on right -->
                        <path id="right-circle-{{ $invoiceId }}" d="M175,100 A75,75 0 0,1 100,175" fill="none" />
                        <text>
                            <textPath href="#right-circle-{{ $invoiceId }}" startOffset="50%" text-anchor="middle" fill="url(#gradient-stamp-{{ $invoiceId }})" font-weight="bold" font-size="14">BOUESTI</textPath>
                        </text>

                        <!-- Circular text on bottom -->
                        <path id="bottom-circle-{{ $invoiceId }}" d="M100,175 A75,75 0 0,1 25,100" fill="none" />
                        <text>
                            <textPath href="#bottom-circle-{{ $invoiceId }}" startOffset="50%" text-anchor="middle" fill="url(#gradient-stamp-{{ $invoiceId }})" font-weight="bold" font-size="14">BOUESTI</textPath>
                        </text>

                        <!-- Circular text on left -->
                        <path id="left-circle-{{ $invoiceId }}" d="M25,100 A75,75 0 0,1 100,25" fill="none" />
                        <text>
                            <textPath href="#left-circle-{{ $invoiceId }}" startOffset="50%" text-anchor="middle" fill="url(#gradient-stamp-{{ $invoiceId }})" font-weight="bold" font-size="14">BOUESTI</textPath>
                        </text>
                        
                        <!-- Stars -->
                        <path d="M100,40 L103,46 L110,47 L105,52 L106,59 L100,56 L94,59 L95,52 L90,47 L97,46 Z" fill="url(#gradient-stamp-{{ $invoiceId }})" />
                        <path d="M40,100 L43,106 L50,107 L45,112 L46,119 L40,116 L34,119 L35,112 L30,107 L37,106 Z" fill="url(#gradient-stamp-{{ $invoiceId }})" />
                        <path d="M160,100 L163,106 L170,107 L165,112 L166,119 L160,116 L154,119 L155,112 L150,107 L157,106 Z" fill="url(#gradient-stamp-{{ $invoiceId }})" />
                        <path d="M100,160 L103,166 L110,167 L105,172 L106,179 L100,176 L94,179 L95,172 L90,167 L97,166 Z" fill="url(#gradient-stamp-{{ $invoiceId }})" />
                        
                        <!-- Center text -->
                        <text x="100" y="95" text-anchor="middle" fill="url(#gradient-stamp-{{ $invoiceId }})" font-weight="bold" font-size="24">APPROVED</text>
                        <text x="100" y="120" text-anchor="middle" fill="url(#gradient-stamp-{{ $invoiceId }})" font-weight="bold" font-size="16">STAMPED</text>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-full w-full p-4 rounded-lg transform transition-all duration-300 group-hover:scale-105 group-hover:shadow-lg" 
                        fill="none"
                        viewBox="0 0 24 24" 
                        stroke="url(#gradient-{{ $invoiceId }})">
                        <defs>
                            <linearGradient id="gradient-{{ $invoiceId }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color: #10B981" /> <!-- Green -->
                                <stop offset="100%" style="stop-color: #F59E0B" /> <!-- Gold -->
                            </linearGradient>
                        </defs>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                @endif
            </div>
            <p class="text-center mt-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ $buttonText }}</p>

            <!-- Preview Popup -->
            <div id="{{ $previewId }}" 
                {{-- class="absolute left-1/2 bottom-full mb-2 -translate-x-1/2 w-64 opacity-0 scale-95 
                       transition-all duration-300 group-hover:opacity-100 group-hover:scale-100 z-10">
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl p-3">
                    <div class="h-32 bg-zinc-100 dark:bg-zinc-700 rounded-lg overflow-hidden">
                        <div class="flex items-center justify-center h-full">
                            <!-- Preview canvas will be injected here -->
                        </div>
                    </div> --}}
                    <p class="text-xs text-center mt-2 text-zinc-500 dark:text-zinc-400">Click to view full PDF</p>
                {{-- </div>
                <div class="w-4 h-4 bg-white dark:bg-zinc-800 transform rotate-45 absolute -bottom-2 left-1/2 -translate-x-1/2"></div> --}}
            </div>
        </div>

        <!-- Modal Dialog -->
        <dialog id="{{ $modalId }}" class="w-11/12 max-w-5xl h-[95vh] rounded-xl backdrop:bg-black/60">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-2xl h-full flex flex-col">
                <div class="p-4 border-b dark:border-zinc-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">Document Preview</h3>
                    <button onclick="document.getElementById('{{ $modalId }}').close()"
                        class="text-zinc-500 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="{{ $viewerId }}" class="flex-1 bg-zinc-100 dark:bg-zinc-800 rounded-b-xl">
                    <div class="flex items-center justify-center h-full w-full">
                        <!-- PDF canvas will be injected here -->
                    </div>
                </div>
            </div>
        </dialog>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

            // Load preview on hover
            document.querySelector('.group').addEventListener('mouseenter', () => {
                loadPDF{{ $invoiceId }}(true);
            });

            async function loadPDF{{ $invoiceId }}(isPreview = false) {
                const container = document.getElementById(isPreview ? '{{ $previewId }}' : '{{ $viewerId }}').querySelector('div');
                container.innerHTML = '<div class="animate-pulse"><div class="w-32 h-32 bg-zinc-200 dark:bg-zinc-600 rounded"></div></div>';

                const url = "{{ app()->environment('local') ? asset('storage/' . $invoiceFile) : asset('storage/app/public/' . $invoiceFile) }}";
                const loadingTask = pdfjsLib.getDocument(url);

                try {
                    const pdf = await loadingTask.promise;
                    const page = await pdf.getPage(1);
                    
                    let scale;
                    if (isPreview) {
                        scale = 0.3;
                    } else {
                        const containerWidth = container.clientWidth;
                        const containerHeight = container.clientHeight;
                        const viewport = page.getViewport({ scale: 1 });
                        const scaleWidth = containerWidth / viewport.width;
                        const scaleHeight = containerHeight / viewport.height;
                        scale = Math.min(scaleWidth, scaleHeight) * 0.95; // 95% of the container
                    }

                    const viewport = page.getViewport({ scale });

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    canvas.classList.add('rounded-lg', 'shadow-lg');

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    container.innerHTML = '';
                    container.appendChild(canvas);
                    await page.render(renderContext);
                } catch (error) {
                    container.innerHTML = '<p class="text-red-500 text-sm">Failed to load PDF.</p>';
                    console.error('Error loading PDF:', error);
                }
            }
        </script>
    @else
        <div class="w-24 h-24 mx-auto relative opacity-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full p-4 rounded-lg" fill="none"
                viewBox="0 0 24 24" stroke="url(#gradient-disabled-{{ $invoiceId }})">
                <defs>
                    <linearGradient id="gradient-disabled-{{ $invoiceId }}" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color: #10B98166" /> <!-- Green with opacity -->
                        <stop offset="100%" style="stop-color: #F59E0B66" /> <!-- Gold with opacity -->
                    </linearGradient>
                </defs>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-center mt-2 text-sm text-red-500">Invoice file not found.</p>
        </div>
    @endif
</div>
