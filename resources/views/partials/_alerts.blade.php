<div class="row">
    <div class="col-xs-12 alerts">
        @if (Session::has('message'))
            <div class="alert alert-info">
                <p>{!! Session::get('message') !!}</p>
            </div>
        @endif
        @if ($errors->count() > 0)
            <div class="alert alert-danger">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::has('success'))
            <div class="alert alert alert-success">
                <p>{!! Session::get('success') !!}</p>
            </div>
        @endif
    </div>
</div>
