@extends('layouts.app')
@section('customcss')
    <style>
    </style>
@endsection
@section('content')
<div class="container">
    <p id="x-position">X position: <span id='x-val'></span> </p>
    <p id="y-position">Y position: <span id='y-val'></span></p>
    <div id="navigation_controls">
        <button type="button" class="btn btn-primary" id="prev">Previous</button>
        <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
        <button type="button" class="btn btn-primary" id="next">Next</button>
    </div>
    <br>
    <button type="button" class="btn btn-primary mb-5" id="btnDraw">Draw Rectangle</button>
    <br>
    <div class="form-row text-center">
        <div class="form-group col-lg-12" style="">
            <div class="form-row text-center mb-5">
                <div class="form-group col-lg-12" style="position: relative">
                    <canvas id="pdf-renderer"
                    style="position: absolute; left: 0; top: 0; z-index: 0;
                    margin-left: auto;
                    margin-right: auto;
                    right: 0;
                    text-align: center;"></canvas>
                    <canvas id="canvas-hover"
                    style="position: absolute; left: 0; top: 0; z-index: 1; border: 1px solid blue;
                    margin-left: auto;
                    margin-right: auto;
                    right: 0;
                    text-align: center;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>


</div>

@endsection

@section('customjs')
    <script src="https://code.createjs.com/1.0.0/createjs.min.js"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script>
        $(document).ready(function(){
            stage = new createjs.Stage("canvas-hover");
            var SIZE = 50;
            $('#btnDraw').click(function(){
                addCircle(canvas.width/2 - (SIZE * 2.5), canvas.height/2, SIZE, "#e74c3c");
                stage.update();

            });
            function addCircle(x, y, r, fill) {
                var circle = new createjs.Shape();
                circle.graphics.beginFill(fill).drawCircle(0, 0, r);
                circle.x = x;
                circle.y = y;
                circle.name = "circle";
                circle.on("pressmove",drag);
                stage.addChild(circle);
            }
            function drag(evt) {
                // target will be the container that the event listener was added to
                if(evt.target.name == "square") {
                    evt.target.x = evt.stageX - SIZE;
                    evt.target.y = evt.stageY - SIZE;
                }
                else  {
                    evt.target.x = evt.stageX;
                    evt.target.y = evt.stageY;
                }

                // make sure to redraw the stage to show the change
                stage.update();
            }
            var url = "{{ route('document.display',['document'=>$document->id]) }}";

            // Loaded via <script> tag, create shortcut to access PDF.js exports.
            var pdfjsLib = window['pdfjs-dist/build/pdf'];

            // The workerSrc property shall be specified.
            pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

            var pdfDoc = null,
                pageNum = 1,
                pageRendering = false,
                pageNumPending = null,
                scale = 1.5,
                canvas = document.getElementById('pdf-renderer'),
                ctx = canvas.getContext('2d'),
                canvasHover = document.getElementById('canvas-hover'),
                ctxHover = canvasHover.getContext('2d');

            /**
             * Get page info from document, resize canvas accordingly, and render page.
             * @param num Page number.
             */
            function renderPage(num) {
                pageRendering = true;
                // Using promise to fetch the page
                pdfDoc.getPage(num).then(function(page) {
                    var viewport = page.getViewport({scale: scale});
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    canvasHover.height = viewport.height;
                    canvasHover.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);

                    // Wait for rendering to finish
                    renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        // New page rendering is pending
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            // Update page counters
            document.getElementById('page_num').textContent = num;
            }

            /**
             * If another page rendering in progress, waits until the rendering is
             * finised. Otherwise, executes rendering immediately.
             */
            function queueRenderPage(num) {
                if (pageRendering) {
                    pageNumPending = num;
                } else {
                    renderPage(num);
                }
            }

            /**
             * Displays previous page.
             */
            function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
            }
            document.getElementById('prev').addEventListener('click', onPrevPage);

            /**
             * Displays next page.
             */
            function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
            }
            document.getElementById('next').addEventListener('click', onNextPage);

            /**
             * Asynchronously downloads PDF.
             */
            pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page_count').textContent = pdfDoc.numPages;

            // Initial/first page rendering
            renderPage(pageNum);
            });


            // $("#pdf-renderer").on('mousemove', function(e) {
            //     var mousex = parseInt(e.clientX-canvas.getBoundingClientRect().left);
            //     var mousey = parseInt(e.clientY-canvas.getBoundingClientRect().top);
            //     $('#x-val').html(mousex)
            //     $('#y-val').html(mousey)
            // });





        });
    </script>
@endsection
