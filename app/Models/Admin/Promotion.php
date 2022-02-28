<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataTables\Admin\PromotionDataTable;
use App\Traits\Translations\Admin\PromotionTranslation;
use App\Traits\Revisionable\Admin\PromotionRevisionable;

class Promotion extends Model
{
    use PromotionDataTable;
   // use PromotionRevisionable;
    use PromotionTranslation;
    use SoftDeletes;

    protected $connection = 'solopertedev';

    protected $table = 'elencopromozioni';

    protected $guarded = [];
}
