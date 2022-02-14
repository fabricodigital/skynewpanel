<?php

namespace App\Traits\Revisionable;

use App\Models\Admin\Revision;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

trait Revisionable
{
    /**
     *
     */
    public static function bootRevisionable()
    {
        self::created(function ($model) {
            $model->revisionableCreate('created');
        });

        self::updated(function ($model) {
            $model->revisionableCreate('updated');
        });

        self::deleted(function ($model) {
            $model->revisionableCreate('deleted');
        });

        if (method_exists(self::class, 'restored')) {
            self::restored(function ($model) {
                $model->revisionableCreate('restored');
            });
        }
    }

    /**
     * @return mixed
     */
    public function revisions()
    {
        return $this->morphMany(Revision::class, 'model');
    }

    /**
     * @param string $type
     */
    protected function revisionableCreate(string $type)
    {
        $old = [];
        $new = [];

        switch ($type) {
            case 'created':
                $new = $this->revisionableAddNew();
                break;
            case 'updated':
                $revisionData = $this->revisionableUpdate();
                $old = $revisionData['old'];
                $new = $revisionData['new'];
                break;
            case 'deleted':
            case 'restored':
                $new = $this->revisionableAddNew();
                $old = $this->revisionableAddNew();
                if ($type == 'deleted' && (!method_exists($this, 'isForceDeleting') || $this->isForceDeleting())) {
                    $type = 'deleted forever';
                }
                break;
        }

        if(!empty($new) || !empty($old)) {
            $this->revisions()->create([
                'creator_id' => Auth::check() ? Auth::id() : null,
                'locale' => Auth::check() ? Auth::user()->locale : null,
                'type' => $type,
                'old' => !empty($old) ? $old : null,
                'new' => !empty($new) ? $new : null,
                'ip' => request()->getClientIp()
            ]);
        }
    }

    /**
     * @return array
     */
    private function revisionableAddNew()
    {
        $output = [];

        if(isset($this->revisionableAttributes)) {
            foreach ($this->revisionableAttributes as $revisionableAttribute) {
                if(isset($this->casts[$revisionableAttribute]) && $this->casts[$revisionableAttribute] == 'array') {
                    $tmpNew = is_array($this->$revisionableAttribute) ? $this->$revisionableAttribute : [];
                    $output[$revisionableAttribute] = $this->revisionableSanitizeValue($revisionableAttribute, $tmpNew);
                }else{
                    $output[$revisionableAttribute] = [$this->revisionableSanitizeValue($revisionableAttribute, $this->$revisionableAttribute)];
                }
            }
        }

        if(isset($this->revisionableBelongsToRelations)) {
            foreach ($this->revisionableBelongsToRelations as $revisionableBelongsToRelation) {
                $relation = $revisionableBelongsToRelation['relation'];
                $relatedTitleAttributes = $revisionableBelongsToRelation['related_title_attributes'];

                if(isset($this->$relation) && !empty($this->$relation)) {
                    $output[$revisionableBelongsToRelation['attribute']] = $this->revisionableExtractRelatedModelTitleAttributes($relatedTitleAttributes, $this->$relation);
                }
            }
        }

        if(isset($this->revisionableManyToManyRelations)) {
            foreach ($this->revisionableManyToManyRelations as $revisionableManyToManyRelation) {
                $inputName = $revisionableManyToManyRelation['input_name'];
                $relatedTitleAttributes = $revisionableManyToManyRelation['related_title_attributes'];
                $relatedClass = '\\' .$revisionableManyToManyRelation['class'];

                $values = request($inputName) ? request($inputName) : [];
                $relatedModels = $relatedClass::whereIn('id', $values)->get();

                if(count($relatedModels)) {
                    $output[$inputName] = $this->revisionableExtractRelatedModelTitleAttributes($relatedTitleAttributes, $relatedModels, true);
                }
            }
        }

        return $output;
    }


    /**
     * @param $attributes
     * @param $related
     * @param bool $many
     * @return array
     */
    private function revisionableExtractRelatedModelTitleAttributes($attributes, $related, $many = false)
    {
        $output = [];

        if(method_exists($related, 'loadTranslations')) {
            $related->loadTranslations();
        }

        if(!$many) {
            $tmp = [];
            foreach ($attributes as $attribute) {
                $tmp []= $related->$attribute;
            }
            $output []= implode(' ', $tmp);
        }else{
            foreach ($related as $model) {
                if(method_exists($model, 'loadTranslations')) {
                    $model->loadTranslations();
                }
                $tmp = [];
                foreach ($attributes as $attribute) {
                    $tmp []= $model->$attribute;
                }
                $output []= implode(' ', $tmp);
            }
        }

        return $output;
    }

    /**
     * @return array
     */
    private function revisionableUpdate():array
    {
        $output = [
            'new' => [],
            'old' => [],
        ];

        $output = $this->revisionableUpdateAttributes($output);
        $output = $this->revisionableUpdateBelongsTo($output);

        return $output;
    }

    /**
     * @param string $attribute
     * @param $value
     * @return string
     */
    private function revisionableSanitizeValue(string $attribute, $value)
    {
        if(isset($this->hidden) && in_array($attribute, $this->hidden)) {
            return 'hidden';
        }

        if(isset($this->dates) && in_array($attribute, $this->dates)) {
            try {
                $sanitizedDate = Carbon::parse($value)->format('d/m/Y H:i:s');
                return $sanitizedDate;
            }catch (Exception $exception) {
                return $value;
            }
        }

        return $value;
    }

    /**
     * @param array $output
     * @return array
     */
    private function revisionableUpdateAttributes(array $output) :array
    {
        if(!isset($this->revisionableAttributes)) {
            return $output;
        }

        foreach ($this->revisionableAttributes as $revisionableAttribute) {
            $original = $this->getRawOriginal();

            if(isset($this->casts[$revisionableAttribute]) && $this->casts[$revisionableAttribute] == 'array') {
                $tmpNew = is_array($this->$revisionableAttribute) ? $this->$revisionableAttribute : [];
                $tmpOld = $original[$revisionableAttribute] ? json_decode($original[$revisionableAttribute], true) : [];

                if(check_arrays_for_differences($tmpNew, $tmpOld)) {
                    $output['new'][$revisionableAttribute] = $this->revisionableSanitizeValue($revisionableAttribute, $tmpNew);
                    $output['old'][$revisionableAttribute] = $this->revisionableSanitizeValue($revisionableAttribute, $tmpOld);
                }
            }elseif(isset($original[$revisionableAttribute]) && $this->$revisionableAttribute != $original[$revisionableAttribute]) {
                $output['new'][$revisionableAttribute] = [$this->revisionableSanitizeValue($revisionableAttribute, $this->$revisionableAttribute)];
                $output['old'][$revisionableAttribute] = [$this->revisionableSanitizeValue($revisionableAttribute, $original[$revisionableAttribute])];
            }
        }

        return $output;
    }

    /**
     * @param array $output
     * @return array
     */
    private function revisionableUpdateBelongsTo(array $output) :array
    {
        if(!isset($this->revisionableBelongsToRelations)) {
            return $output;
        }

        foreach ($this->revisionableBelongsToRelations as $revisionableBelongsToRelation) {
            $attribute = $revisionableBelongsToRelation['attribute'];
            $relatedClass = '\\' .$revisionableBelongsToRelation['class'];
            $relatedTitleAttributes = $revisionableBelongsToRelation['related_title_attributes'];

            $original = $this->getRawOriginal();
            if($this->$attribute != $original[$attribute]) {
                $old = $relatedClass::find($original[$attribute]);
                $new = $relatedClass::find($this->$attribute);

                $output['old'][$attribute] = $old ? $this->revisionableExtractRelatedModelTitleAttributes($relatedTitleAttributes, $old ) : [null];
                $output['new'][$attribute] = $new ? $this->revisionableExtractRelatedModelTitleAttributes($relatedTitleAttributes, $new ) : [null];
            }
        }

        return $output;
    }

    /**
     * @param array $data
     */
    public function revisionableUpdateManyToMany(array $data)
    {
        if(isset($this->revisionableManyToManyRelations)) {

            $new = [];
            $old = [];

            foreach ($this->revisionableManyToManyRelations as $revisionableManyToManyRelation) {
                $inputName = $revisionableManyToManyRelation['input_name'];
                $relatedTitleAttributes = $revisionableManyToManyRelation['related_title_attributes'];
                $relatedClass = '\\' .$revisionableManyToManyRelation['class'];
                $relation = $revisionableManyToManyRelation['relation'];

                $values = isset($data[$inputName]) && !empty($data) ? $data[$inputName] : [];

                if(isset($revisionableManyToManyRelation['with_pivot']) && $revisionableManyToManyRelation['with_pivot'] == true) {
                    $values = array_keys($values);
                }

                $relatedOldModels = $this->$relation()->get();
                $relatedNewModels = $relatedClass::whereIn('id', $values)->get();

                $relatedOldIds = $relatedOldModels->pluck('id')->toArray();
                $relatedNewIds = $relatedNewModels->pluck('id')->toArray();


                if( !empty(array_diff($relatedOldIds, $relatedNewIds)) || !empty(array_diff($relatedNewIds, $relatedOldIds)) ) {
                    $old[$inputName] = $this->revisionableExtractRelatedModelTitleAttributes($relatedTitleAttributes, $relatedOldModels, true );
                    $new[$inputName] = $this->revisionableExtractRelatedModelTitleAttributes($relatedTitleAttributes, $relatedNewModels, true );
                }

            }

            if(!empty($new) || !empty($old)) {
                $this->revisions()->create([
                    'creator_id' => Auth::check() ? Auth::id() : null,
                    'locale' => Auth::check() ? Auth::user()->locale : null,
                    'type' => 'updated',
                    'old' => !empty($old) ? $old : null,
                    'new' => !empty($new) ? $new : null,
                    'ip' => request()->getClientIp()
                ]);
            }
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasRevisionableMediaCollection(string $key)
    {
        if (!empty($this->revisionableMediaCollections) && in_array($key, $this->revisionableMediaCollections)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @return string
     */
    public static function getRevisionableTemplate(string $key):string
    {
        if (isset(self::$revisionableEnums) && in_array($key, self::$revisionableEnums)) {
            return 'admin.datatables.partials.revisions.templates._enums';
        } elseif (isset(self::$revisionableBooleans) && in_array($key, self::$revisionableBooleans)) {
            return 'admin.datatables.partials.revisions.templates._booleans';
        } elseif (isset(self::$revisionableTags) && in_array($key, self::$revisionableTags)) {
            return 'admin.datatables.partials.revisions.templates._tags';
        } elseif (isset(self::$revisionableColor) && in_array($key, self::$revisionableColor)) {
            return 'admin.datatables.partials.revisions.templates._color';
        } elseif (isset(self::$revisionableJsons) && array_key_exists($key, self::$revisionableJsons)) {
            if (isset(self::$revisionableJsons[$key]['template'])) {
                return view()->exists('admin.datatables.partials.revisions.templates.custom_jsons._' . self::$revisionableJsons[$key]['template'])
                    ? 'admin.datatables.partials.revisions.templates.custom_jsons._' . self::$revisionableJsons[$key]['template']
                    : 'admin.datatables.partials.revisions.templates._json';
            }
            return 'admin.datatables.partials.revisions.templates._json';
        } else{
            return 'admin.datatables.partials.revisions.templates._default';
        }
    }
}
