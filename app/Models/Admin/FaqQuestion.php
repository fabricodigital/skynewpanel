<?php

namespace App\Models\Admin;

use App\Traits\MultiTenant\AccountTenant;
use App\Traits\Revisionable\Admin\FaqQuestionRevisionable;
use App\Traits\Translations\Admin\FaqQuestionTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\Traits\DataTables\Admin\FaqQuestionDataTable;

class FaqQuestion extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use FaqQuestionRevisionable;
    use FaqQuestionDataTable;
    use FaqQuestionTranslation;
    use AccountTenant;

    protected $guarded = ['attachments'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }

    /**
     *
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('attachments');
    }
}
