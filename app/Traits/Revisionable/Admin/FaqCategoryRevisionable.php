<?php

namespace App\Traits\Revisionable\Admin;

use App\Traits\Revisionable\Revisionable;

trait FaqCategoryRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['title'];

    public static $revisionableRoute = 'admin.faq-categories.show';
}

