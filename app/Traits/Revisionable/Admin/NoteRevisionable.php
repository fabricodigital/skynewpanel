<?php

namespace App\Traits\Revisionable\Admin;

use App\Traits\Revisionable\Revisionable;

trait NoteRevisionable
{
    use Revisionable;

    protected $revisionableAttributes = ['notes'];

    public static $revisionableRoute = 'admin.notes.show';
}

