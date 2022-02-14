<?php

namespace App\Traits\SoftDeletes;

use Illuminate\Support\Str;

trait CascadeSoftDeletes
{
    /**
     *
     */
    protected static function bootCascadeSoftDeletes()
    {
        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $model->validateCascadingSoftDelete();
                $model->runCascadingDeletes();
            }
        });

        static::restoring(function ($model) {
            if (request('restore_relations')) {
                $model->validateCascadingSoftDelete();
                $model->runCascadingRestores();
            }
        });
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function validateCascadingSoftDelete()
    {
        if (!method_exists($this, 'runSoftDelete')) {
            throw new \Exception(sprintf('%s does not implement Illuminate\Database\Eloquent\SoftDeletes', $this));
        }

        $invalidRelationships = array_filter($this->getCascadingDeletes(), function ($relationship) {
            return ! method_exists($this, $relationship);
        });
        if (!empty($invalidRelationships)) {
            throw new \Exception(sprintf(
                '%s [%s] must exist and return an object of type Illuminate\Database\Eloquent\Relations\Relation',
                Str::plural('Relationship', count($invalidRelationships)),
                join(', ', $invalidRelationships)
            ));
        }
    }

    /**
     *
     */
    protected function runCascadingDeletes()
    {
        foreach ($this->getActiveCascadingDeletes() as $relationship) {
            foreach ($this->{$relationship}()->get() as $model) {
                $model->pivot ? /*$model->pivot->delete()*/ false : $model->delete();
            }
        }
    }

    /**
     *
     */
    protected function runCascadingRestores()
    {
        foreach ($this->getActiveCascadingDeletes() as $relationship) {
            foreach ($this->{$relationship}()->withTrashed()->get() as $model) {
                $model->pivot ? $model->pivot->restore() : $model->restore();
            }
        }
    }

    /**
     * @return array
     */
    protected function getCascadingDeletes()
    {
        return isset($this->cascadeDeletes) ? (array) $this->cascadeDeletes : [];
    }


    /**
     * @return array
     */
    protected function getActiveCascadingDeletes()
    {
        return array_filter($this->getCascadingDeletes(), function ($relationship) {
            return ! is_null($this->{$relationship});
        });
    }
}
