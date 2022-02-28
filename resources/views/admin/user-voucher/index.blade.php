@extends('layouts.admin')

@section('title', __('Search'))

@section('content')
@include('partials._content-heading', ['title' => __('Search')])

@include('partials._alerts')

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="">
                <form class="col-md-6 col-md-offset-3" action="{{route('admin.searchclient')}}" method="POST">
                    @csrf
                    @include('partials.inputs._text', [
                        'name' => 'infocode',
                        'label' => '',
                        'width' => 10,
                        'attributes' => ['maxlength' => 25],
                    ])
                    <div class="col-md-6 col-md-offset-2 form-group ">
                        <button class="form-control btn btn-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
        @isset($uservouch)
            @include('admin.user-voucher.partials._tables-result')
        @endisset
    </div>
</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Image preview</h4>
            </div>
            <div class="modal-body">
                <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <script>
        $("#pop").on("click", function() {
            $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
            $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
        });
    </script>
@stop




