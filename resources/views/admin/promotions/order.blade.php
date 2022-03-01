@extends('layouts.admin')

@section('title', App\Models\Admin\Promotion::getTitleTrans())

@section('content')

    @include('partials._content-heading', ['title' => App\Models\Admin\Promotion::getTitleTrans()])

    @include('partials._alerts')
    <style type="text/css">
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
                <div class="pull-right">
                    <h4>{{$promotionsCount}}</h4>
                </div>
                <div class="col-lg-10"  id="image-list" >
                    @foreach($promotions as $p)
                        <div class="alert alert-success lis" id="img{{$p->id}}" draggable="true" role="alert" style=" min-height: 72px ;border: 4px solid rgba(11, 11, 11, 0.4) !important;background-color: transparent !important;color: black !important;">
                            <p class="pull-left">{{$p->nome}} </p> <p class="pull-right">{{$p->datafine}}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>


    <script type="application/javascript">
        $(document).ready(function () {
            var dropIndex;
            $("#image-list").sortable({
                update: function(event, ui) {
                    dropIndex = ui.item.index();
                }
            });
        });
    </script>
@stop


