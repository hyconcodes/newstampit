@props(['invoiceId', 'stampedFile'])

@php
    $modalId = 'stamped-pdf-modal-' . $invoiceId;
    $viewerId = 'stamped-pdf-viewer-' . $invoiceId;
    $previewId = 'stamped-pdf-preview-' . $invoiceId;
    $downloadUrl = url('/download/stamped-invoice/' . $invoiceId);
@endphp

<div class="contents">
    <div class="relative group cursor-pointer">
        <!-- Main Icon -->
        <div class="w-full h-full mx-auto" onclick="document.getElementById('{{ $modalId }}').showModal(); loadStampedPDF{{ $invoiceId }}(false);">
            <div class="flex items-center justify-center h-32">
                <div class="text-center">
                    <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto mb-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex items-center justify-center">
                        <span class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-full">
                            ✓ STAMPED
                        </span>
                    </div>
                    <p class="text-xs text-green-700 dark:text-green-300 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        Click to preview
                    </p>
                </div>
            </div>
        </div>

        <!-- Preview Popup on Hover -->
        <div id="{{ $previewId }}" 
            class="absolute left-1/2 bottom-full mb-2 -translate-x-1/2 w-64 opacity-0 scale-95 
                   transition-all duration-300 group-hover:opacity-100 group-hover:scale-100 z-10 pointer-events-none">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl p-3">
                <div class="h-32 bg-zinc-100 dark:bg-zinc-700 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-center h-full">
                        <!-- Preview canvas will be injected here -->
                    </div>
                </div>
                <p class="text-xs text-center mt-2 text-zinc-500 dark:text-zinc-400">Click to view full PDF</p>
            </div>
            <div class="w-4 h-4 bg-white dark:bg-zinc-800 transform rotate-45 absolute -bottom-2 left-1/2 -translate-x-1/2"></div>
        </div>
    </div>

    <!-- Modal Dialog -->
    <dialog id="{{ $modalId }}" class="w-11/12 max-w-5xl h-[95vh] rounded-xl backdrop:bg-black/60">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-2xl h-full flex flex-col">
            <div class="p-4 border-b dark:border-zinc-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">Stamped Receipt Preview</h3>
                <div class="flex items-center space-x-2">
                    <a href="{{ $downloadUrl }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download
                    </a>
                    <button onclick="document.getElementById('{{ $modalId }}').close()"
                        class="p-2 text-zinc-500 hover:text-red-500 dark:text-zinc-400 dark:hover:text-red-400 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
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
        document.querySelector('#{{ $previewId }}').parentElement.addEventListener('mouseenter', () => {
            loadStampedPDF{{ $invoiceId }}(true);
        });

        async function loadStampedPDF{{ $invoiceId }}(isPreview = false) {
            const container = document.getElementById(isPreview ? '{{ $previewId }}' : '{{ $viewerId }}').querySelector('div');
            container.innerHTML = '<div class="animate-pulse flex items-center justify-center h-full"><div class="w-16 h-16 bg-zinc-200 dark:bg-zinc-600 rounded"></div></div>';

            const url = "{{ $downloadUrl }}";
            
            try {
                const loadingTask = pdfjsLib.getDocument(url);
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
                    scale = Math.min(scaleWidth, scaleHeight) * 0.95;
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
                container.innerHTML = '<p class="text-red-500 text-sm">Failed to load PDF preview.</p>';
                console.error('Error loading stamped PDF:', error);
            }
        }
    </script>
</div>
