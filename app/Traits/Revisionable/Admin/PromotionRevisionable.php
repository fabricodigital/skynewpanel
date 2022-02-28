<?php

namespace App\Traits\Revisionable\Admin;

use App\Traits\Revisionable\Revisionable;

trait PromotionRevisionable
{
    use Revisionable;


    protected $connection = 'mysql';


    protected $revisionableAttributes = ['nome'];

    public static $revisionableRoute = 'admin.promotions.show';
}

