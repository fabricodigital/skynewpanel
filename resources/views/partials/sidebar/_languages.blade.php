@foreach(config('main.available_languages') as $abbr => $label)
    <li class="{{ Auth::user()->locale == $abbr ? 'active' : '' }}">
        <a href="{{ route('admin.profile.locale', ['locale' => $abbr]) }}">
            <i class="flag-icon flag-icon-{{ $abbr != 'en' ? $abbr : 'gb' }}"></i>
            <span class="title">{{ __($label) }}</span>
        </a>
    </li>
@endforeach
