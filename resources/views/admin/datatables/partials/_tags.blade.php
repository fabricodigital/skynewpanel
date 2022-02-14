@foreach($tags as $tag)
    @if(!empty($tag))
        <span class="label {{ $labelClasses }}">{{ $tag }}</span>
    @endif
@endforeach