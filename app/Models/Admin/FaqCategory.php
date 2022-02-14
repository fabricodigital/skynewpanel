<?php

namespace App\Models\Admin;

use App\Traits\MultiTenant\AccountTenant;
use App\Traits\SoftDeletes\CascadeSoftDeletes;
use App\Traits\Translations\Admin\FaqCategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Revisionable\Admin\FaqCategoryRevisionable;
use App\Traits\DataTables\Admin\FaqCategoryDataTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqCategory extends Model
{
    use FaqCategoryDataTable;
    use FaqCategoryRevisionable;
    use FaqCategoryTranslation;
    use AccountTenant;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $guarded = [];

    protected $cascadeDeletes = ['questions'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(FaqQuestion::class, 'category_id');
    }
}
