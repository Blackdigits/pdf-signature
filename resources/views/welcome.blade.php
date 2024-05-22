@if(isset($pdfUrl))
    <script>
        var pdfUrl = "{{ $pdfUrl }}";
        if (window.parent) { 
            window.parent.postMessage(pdfUrl, "*"); 
        } else { console.log(pdfUrl); } 
        window.location.href = "{{ $pdfUrl }}";
    </script>
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>e-Signature</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <style>
        #canvas-container {
            position: relative;
            width: max-content;
            height: max-content;
            padding: 0px;
        }
        .draggable {
            display: none;
            width: 100px;
            height: 75px;
            background-color: red;
            touch-action: none;
            user-select: none;
            text-align: center;
            padding: 30px 0;
            color: white;
            position: absolute;
        }
    </style>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex-col justify-center dark:text-white">   

                    <div class="relative flex w-full max-w-[24rem]">
                        <div class="relative h-10 w-full min-w-[200px]">
                            <input type="number" id="pageNumberInput" min="1"
                            class="peer h-full w-full rounded-[7px] border border-blue-gray-200 border-t-transparent bg-transparent px-3 py-2.5 pr-20 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
                            placeholder="" />
                            <label
                            class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none !overflow-visible truncate text-[11px] font-normal leading-tight text-gray-500 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-900 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:!border-gray-900 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:!border-gray-900 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                            Halaman
                            </label>
                        </div>
                        <button
                            class="!absolute right-1 top-1 select-none rounded bg-blue-500 py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-gray-500/20 transition-all hover:shadow-lg hover:shadow-blue-gray-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            type="button" onclick="showPage()">
                            Tampilkan
                        </button>
                    </div>  
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="flex w-full bg-grey-lighter" id="canvas-container">
                            <div class="draggable"> Lokasi </div>
                            <canvas class="border-solid border-2 dark:border-zinc-50" id="pdf-canvas"> ~ PDF ~</canvas>
                        </div> 
                        <input type="hidden" id="pageID" name="pageID">
                        <input type="hidden" id="stampX" name="stampX">
                        <input type="hidden" id="stampY" name="stampY">
                        <input type="hidden" id="canvasHeight" name="canvasHeight">
                        <input type="hidden" id="canvasWidth" name="canvasWidth">
                        <input type="hidden" value="{{ request()->query('url') }}" name="urlPdf">
                        <input type="hidden" value="{{ request()->query('sign') }}" name="Signature">
                        {{-- btn --}}
                        <div class="flex w-full items-center justify-center bg-black-#334155">
                            <button type="submit" class="w-32 mt-3 flex flex-col items-center bg-black-#334155 dark:bg-black-#334155 text-blue rounded-lg shadow-lg tracking-wide border border-blue cursor-pointer hover:bg-black hover:text-white">
                                <span class="mt-2 text-base dark:text-white leading-normal">Kirim</span>
                            </button>
                        </div>
                        <br /><br />
                    </form>
                    <!-- 
                        <div class="flex w-full items-center justify-center bg-grey-lighter">
                            <label
                                class="w-64 flex flex-col items-center px-4 py-6 bg-white dark:bg-grey-lighter text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-white">
                                <svg class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                </svg>
                                <span class="mt-2 text-base dark:text-black leading-normal">Ganti Tanda Tangan</span>
                                <input type='file' name="pdf-file" class="hidden" id="document-result" accept=".png" required/>
                                <hr>
                            </label>
                        </div>  -->
                </div>
                <div class="bottom-0 left-0 right-0 z-40 px-4 py-3 text-center text-white bg-gray-400" style="position: absolute!important">
                    <a href="https://cloud-rise.tech class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                    ©2024 PT. Cloud Rise Technology -   </a>   &nbsp;   Parpetual Documentation v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
        </div>
    </body>

    <script type="module" src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.269/pdf.min.mjs'></script>
    <script type="module" src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.269/pdf.worker.min.mjs'></script>

    <script>
        //document.querySelector("#document-result").addEventListener("change", async function(e){}
        function showPage() {
            var pdfUrl = "{{ request()->query('url') }}";
            
            // Menggunakan pdfjsLib.getDocument(url) dengan URL yang diberikan.
            const loadingTask = pdfjsLib.getDocument(pdfUrl);
            loadingTask.promise.then(pdf => {
                // Anda dapat menggunakan *pdf* di sini
                var pageNumber = parseInt(document.getElementById('pageNumberInput').value);
                if (pageNumber < 1 || pageNumber > pdf.numPages) {
                    alert("Nomor halaman tidak valid!");
                    return;
                }
                document.getElementById('pageNumberInput').placeholder='1 - '+pdf.numPages;
                document.getElementById('pageID').value = pageNumber;
                pdf.getPage(pageNumber).then(function(page) {
                    
                    var scale = 1.5; // Scale harus sesuai dengan backend
                    var viewport = page.getViewport({ scale: scale });
                    var canvas = document.getElementById('pdf-canvas'); 

                    canvas.classList.remove('border-2'); 
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    document.getElementById('canvasHeight').value = viewport.height;
                    document.getElementById('canvasWidth').value = viewport.width;

                    page.render({
                        canvasContext: canvas.getContext('2d'),
                        viewport: viewport
                    });
                });
            });

            document.getElementsByClassName('draggable')[0].style.display = 'block';
        };
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.20/dist/interact.min.js"></script>

    <script>
        const position = { x: 0, y: 0 }
        interact('.draggable').draggable({
            listeners: {
                move (event) {
                    position.x += event.dx
                    position.y += event.dy

                    event.target.style.transform =
                        `translate(${position.x}px, ${position.y}px)`
                },
                end (event) {
                    var style = window.getComputedStyle(event.target);
                    var matrix = new WebKitCSSMatrix(style.transform);

                    console.log(matrix.m41, matrix.m42)
                    document.getElementById('stampX').value = matrix.m41;
                    document.getElementById('stampY').value = matrix.m42;
                }
            },
            inertia: true,
            modifiers: [
                interact.modifiers.restrictRect({
                    restriction: 'parent',
                    endOnly: true
                })
            ],
        })
    </script>
</html>
@endif