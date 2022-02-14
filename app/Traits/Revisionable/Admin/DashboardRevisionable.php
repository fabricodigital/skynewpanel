<?php

namespace App\Traits\Revisionable\Admin;

use App\Models\Admin\Widget;
use App\Traits\Revisionable\Revisionable;

trait DashboardRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['name', 'role_id', 'account_id'];

    public static $revisionableRoute = 'admin.dashboards.show';
}

