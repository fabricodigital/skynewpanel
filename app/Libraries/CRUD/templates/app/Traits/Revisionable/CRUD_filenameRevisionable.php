<?php

namespace App\Traits\Revisionable\Admin;

use App\Traits\Revisionable\Revisionable;

trait CRUD_filenameRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['CRUD_column_name'];

    public static $revisionableRoute = 'admin.CRUD_route.show';
}

