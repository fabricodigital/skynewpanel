<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\Models\Media as BaseMedia;
use function app;

class Media extends BaseMedia
{
    public static function boot()
    {
        @parent::boot();

        self::creating(function($media){
            if(
                isset($media->model->translatableMediaCollections)
                && in_array($media->collection_name, $media->model->translatableMediaCollections)
            ) {
                $media->setCustomProperty('locale', app()->getLocale());

            }
        });
        self::created(function($media) {
            self::createRevision($media, 'created');
        });

        self::deleted(function($media) {
            self::createRevision($media, 'deleted');
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('media_trans', function (Builder $builder) {
            $builder->where(function ($query) {
                $query->whereRaw("JSON_EXTRACT(custom_properties, '$.locale') = ? OR JSON_EXTRACT(custom_properties, '$.locale') IS NULL", [app()->getLocale()]);
            });
        });
    }

    /**
     * @param $media
     * @param $type
     * @return bool
     */
    private static function createRevision($media, $type)
    {
        $model = $media->model;

        if (!method_exists($model, 'hasRevisionableMediaCollection')
            || !$model->hasRevisionableMediaCollection($media->collection_name)
        ) {
            return false;
        }

        $data = [
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'ip' => request()->getClientIp(),
            'creator_id' => Auth::check() ? Auth::id() : null,
            'locale' => Auth::check() ? Auth::user()->locale : null,
        ];

        if ($type == 'created') {
            $data['type'] = 'created';
            $data['old'] = null;
            $data['new'] = [$media->collection_name . '-form-label' => [$media->file_name]];
        } else if ($type == 'deleted') {
            $data['type'] = 'deleted forever';
            $data['old'] = [$media->collection_name . '-form-label' => [$media->file_name]];
            $data['new'] = null;
        }

        Revision::create($data);
    }
}
