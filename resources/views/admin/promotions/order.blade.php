@extends('layouts.admin')

@section('title', App\Models\Admin\Promotion::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Promotion::getTitleTrans()])

    @include('partials._alerts')
    <style>
        [draggable] {
            -moz-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            /* Required to make elements draggable in old WebKit */
            -khtml-user-drag: element;
            -webkit-user-drag: element;
        }
    </style>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-10"  id="columns" >
                    @foreach($promotions as $p)
                        <div class="alert alert-success column" draggable="true" role="alert" style=" min-height: 72px ;border: 4px solid #00a65a !important;background-color: transparent !important;color: black !important;">
                            <p class="pull-left">{{$p->nome}} </p> <p class="pull-right">{{$p->datafine}}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>


    <script type="text/javascript">
        var dragSrcEl = null;

        function handleDragStart(e) {
            // Target (this) element is the source node.
            dragSrcEl = this;

            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.outerHTML);

            this.classList.add('dragElem');
        }
        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault(); // Necessary. Allows us to drop.
            }
            this.classList.add('over');

            e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

            return false;
        }

        function handleDragEnter(e) {
            // this / e.target is the current hover target.
        }

        function handleDragLeave(e) {
            this.classList.remove('over');  // this / e.target is previous target element.
        }

        function handleDrop(e) {
            // this/e.target is current target element.

            if (e.stopPropagation) {
                e.stopPropagation(); // Stops some browsers from redirecting.
            }

            // Don't do anything if dropping the same column we're dragging.
            if (dragSrcEl != this) {
                // Set the source column's HTML to the HTML of the column we dropped on.
                //alert(this.outerHTML);
                //dragSrcEl.innerHTML = this.innerHTML;
                //this.innerHTML = e.dataTransfer.getData('text/html');
                this.parentNode.removeChild(dragSrcEl);
                var dropHTML = e.dataTransfer.getData('text/html');
                this.insertAdjacentHTML('beforebegin',dropHTML);
                var dropElem = this.previousSibling;
                addDnDHandlers(dropElem);

            }
            this.classList.remove('over');
            return false;
        }

        function handleDragEnd(e) {
            // this/e.target is the source node.
            this.classList.remove('over');

            /*[].forEach.call(cols, function (col) {
              col.classList.remove('over');
            });*/
        }

        function addDnDHandlers(elem) {
            elem.addEventListener('dragstart', handleDragStart, false);
            elem.addEventListener('dragenter', handleDragEnter, false)
            elem.addEventListener('dragover', handleDragOver, false);
            elem.addEventListener('dragleave', handleDragLeave, false);
            elem.addEventListener('drop', handleDrop, false);
            elem.addEventListener('dragend', handleDragEnd, false);

        }

        var cols = document.querySelectorAll('#columns .column');
        [].forEach.call(cols, addDnDHandlers);

    </script>
@stop


