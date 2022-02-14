<?php

namespace App\Traits\Revisionable\Admin;

use App\Traits\Revisionable\Revisionable;

trait WidgetRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['name', 'description', 'type', 'query'];

    public static $revisionableRoute = 'admin.widgets.show';
}

