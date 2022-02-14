@foreach($buttons as $button)
    <a href="{{ $button['route'] }}" class="btn {{ $button['classes'] }}" style="margin-right: 5px;" target="_blank">{{ $button['text'] }}</a>
@endforeach