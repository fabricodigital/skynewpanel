<?php

namespace App\Traits\Discussions;

use App\Models\Admin\Discussion;

trait HasDiscussion
{
    /**
     *
     */
    public static function bootHasDiscussion()
    {
        self::deleted(function ($model) {
            self::deleteDiscussion(self::class, $model->id);
        });
    }

    /**
     * @param $modelType
     * @param $modelId
     */
    private static function deleteDiscussion($modelType, $modelId)
    {
        Discussion::where('model_id', $modelId)
            ->where('model_type', $modelType)
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function discussions()
    {
        return $this->morphMany(Discussion::class, 'model');
    }

    /**
     * @param $perPage
     * @return mixed
     */
    public function getPaginatedComments($perPage)
    {
        return $this->discussions()
            ->select(['discussions.id', 'discussions.comment', 'discussions.created_at', 'users.name as creator_name', 'users.surname as creator_surname'])
            ->join('users', 'users.id', 'discussions.creator_id')
            ->orderBy('discussions.created_at', 'DESC')
            ->paginate($perPage);
    }
}
