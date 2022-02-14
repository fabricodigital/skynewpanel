<?php

namespace App\Models\Admin;

use App\Traits\Translations\Admin\CRUD_filenameTranslation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Revisionable\Admin\CRUD_filenameRevisionable;
use App\Traits\DataTables\Admin\CRUD_filenameDataTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CRUD_filename extends Model
{
    use CRUD_filenameDataTable;
    use CRUD_filenameRevisionable;
    use CRUD_filenameTranslation;
    use SoftDeletes;

    protected $guarded = [];
}
