@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible col-md-4 pull-right">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="icon fa fa-check"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible col-md-4 pull-right">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="icon fa fa-ban"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-dismissible col-md-4 pull-right">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="icon fa fa-warning"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info alert-dismissible col-md-4 pull-right">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="icon fa fa-info"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif