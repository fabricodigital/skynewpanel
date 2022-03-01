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
                <ul id="image-list">
                    @foreach($promotions as $im)

                        <div class="col-sm-6 col-md-4" style="width: 200px" id="img{{$im->id}}"  >
                            <div class="thumbnail">
                                <li style="color: transparent;" class="lis"  id="image_{{$im->id}}">
                                    <button onclick="deleteimage({{$im->id}})" class="delete-file"   style="background: rgb(255, 4, 4);padding: 0.09rem 0.25em 0px;text-align: center;
                               border-radius: 50%;z-index: 199;color: rgb(255, 255, 255);"><i class="glyphicon glyphicon-remove"></i></button>
                                    <p style="width: 150px; height: 150px;" class="img-responsive" alt=""  >{{$im->nome}}</p>
                                </li>
                            </div>
                        </div>

                    @endforeach
                </ul>
{{--                <div class="col-lg-10"  id="columns" >--}}
{{--                    @foreach($promotions as $p)--}}
{{--                        <div class="alert alert-success column" draggable="true" role="alert" style=" min-height: 72px ;border: 4px solid #00a65a !important;background-color: transparent !important;color: black !important;">--}}
{{--                            <p class="pull-left">{{$p->nome}} </p> <p class="pull-right">{{$p->datafine}}</p>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
            </div>

        </div>
    </div>


    <script type="text/javascript">
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


