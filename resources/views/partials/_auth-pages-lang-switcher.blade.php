<div class="form-group margin-bottom-none lang-switcher">
    <div class="col-md-12 text-center">
        @foreach(config('main.available_languages') as $abbr => $label)
            <a href="{{ route('guest-locale', [$abbr]) }}" title="{{ __($label) }}">
                <i class="flag-icon flag-icon-{{ $abbr != 'en' ? $abbr : 'gb' }}"></i>
            </a>
        @endforeach
    </div>
</div>
