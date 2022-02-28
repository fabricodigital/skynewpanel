<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DataTables\Admin\PromotionDataTable;
use App\Traits\Revisionable\Admin\PromotionRevisionable;
use App\Traits\Translations\Admin\PromotionTranslation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Promotion extends Model
{
    use PromotionDataTable;
    use PromotionRevisionable;
    use PromotionTranslation;
    //use SoftDeletes;


    protected $connection = 'solopertedev';

    protected $table = 'elencopromozioni';
}
