<li title="{{ implode(', ', $value) }}">
    <b>{{ (class_exists($model) && $model::getAttrsTrans($key)) ? $model::getAttrsTrans($key) : __($key) }}:</b> {{ implode(', ', $value)  }}
</li>
