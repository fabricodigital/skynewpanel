<?php

namespace App\Traits\Revisionable\Admin;

use App\Traits\Revisionable\Revisionable;

trait AccountRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['name'];

    public static $revisionableRoute = 'admin.accounts.show';
}

