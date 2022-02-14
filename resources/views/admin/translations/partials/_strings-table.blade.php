<table class="table table-bordered table-striped nowrap" id="translations-dt">
    <thead>
        <tr>
            <th style="font-weight: bold;">@lang('String')</th>
            @foreach($langNames as $langName)
                <th style="font-weight: bold;">@lang($langName)</th>
            @endforeach
            <th style="font-weight: bold;">@lang('Translated')</th>
        </tr>
    </thead>
    <tbody>
    @foreach($strings as $key => $val)
        <tr data-key="{{ $key }}">
            <td field-key="key" title="{{ $key }}">{!! $key !!}</td>
            @foreach($langKeys as $langIndex => $langKey)
                <td field-key="{{ $langKey }}" title="{{ isset($val[$langIndex]) ? $val[$langIndex] : '' }}">
                    {!! isset($val[$langIndex]) ? $val[$langIndex] : '' !!}
                </td>
            @endforeach
            <td field-key="translated" data-order="{{ count(array_unique($val)) == count($val) }}">
                @if(count(array_unique($val)) == count($val))
                    <span class="label bg-green">@lang('Yes')</span>
                @else
                    <span class="label bg-red">@lang('No')</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
