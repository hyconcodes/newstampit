@props(['invoiceId', 'invoiceFile'])

@if ($invoiceFile && Storage::exists($invoiceFile))
    <div id="pdf-viewer-{{ $invoiceId }}" class="mb-6 h-48 sm:h-56 flex items-center justify-center bg-zinc-100 dark:bg-zinc-700 rounded-lg overflow-hidden"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

        async function loadPDF{{ $invoiceId }}() {
            const url = "{{ asset('storage/' . $invoiceFile) }}";
            const loadingTask = pdfjsLib.getDocument(url);

            try {
                const pdf = await loadingTask.promise;
                const page = await pdf.getPage(1);

                const scale = 1.5;
                const viewport = page.getViewport({ scale });

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.classList.add('rounded-lg');

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                document.getElementById('pdf-viewer-{{ $invoiceId }}').appendChild(canvas);
                await page.render(renderContext);
            } catch (error) {
                console.error('Error loading PDF:', error);
            }
        }

        loadPDF{{ $invoiceId }}();
    </script>
@else
    <div class="text-6xl text-green-500 dark:text-green-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>
@endif
