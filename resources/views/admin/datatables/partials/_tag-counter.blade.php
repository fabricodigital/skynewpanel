@if($counter > 0)
    <span class="label label-success"><strong>{{ $counter }}</strong></span>
@else
    <span class="label label-danger"><strong>{{ $counter }}</strong></span>
@endif