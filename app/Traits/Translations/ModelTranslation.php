<?php

namespace App\Traits\Translations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait ModelTranslation
{
    /**
     *
     */
    protected static function bootModelTranslation()
    {
        static::addGlobalScope('model_trans', function (Builder $builder) {
            $model = new static();
            $transTable = $model->getTranslationTable();

            $builder->leftJoin($transTable, function($join) use ($model, $transTable) {
                $join->on(
                        $model->getTable() . '.' . $model->getKeyName(),
                        '=',
                        $transTable . '.' . $model->getTranslationRelationField()
                    )
                    ->where(
                        $transTable . '.' . $model->getTranslationLocaleField(),
                        Auth::check() ? Auth::user()->locale : app()->getLocale()
                    );
            });
        });
    }

    /**
     * @return mixed
     */
    public static function withoutTrans()
    {
        return self::withoutGlobalScope('model_trans');
    }

    /**
     * @return string
     */
    private function getTranslationTable()
    {
        if (!empty($this->translationTable)) {
            return $this->translationTable;
        }
        return $this->getTable() . '_trans';
    }

    /**
     * @return string
     */
    private function getTranslationRelationField()
    {
        if (!empty($this->translationRelationField)) {
            return $this->translationRelationField;
        }
        return 'model_id';
    }

    /**
     * @return string
     */
    private function getTranslationLocaleField()
    {
        if (!empty($this->translationLocaleField)) {
            return $this->translationLocaleField;
        }
        return 'locale';
    }

    /**
     * @return array
     */
    private function getTranslationAttributes()
    {
        if (!empty($this->translationAttributes) && is_array($this->translationAttributes)) {
            return $this->translationAttributes;
        }
        return [];
    }

    /**
     * @param array $data
     * @return Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function createTranslated(array $data) : Model
    {
        $transData = [];
        $model = new static();
        foreach ($model->getTranslationAttributes() as $translationAttribute) {
            if (array_key_exists($translationAttribute, $data)) {
                $transData[$translationAttribute] = $data[$translationAttribute];
                unset($data[$translationAttribute]);
            }
        }

        DB::beginTransaction();

        try {
            $model = static::withoutEvents(function () use ($data) {
                if (in_array('App\Traits\MultiTenant\AccountTenant', class_uses(self::class))) {
                    $data['account_id'] = Auth::user()->account_id;
                }
                return self::create($data);
            });

            if (!empty($transData)) {
                $transData[$model->getTranslationRelationField()] = $model->id;

                $locales = config('main.available_languages');
                foreach ($locales as $locale => $label) {
                    $transData[$model->getTranslationLocaleField()] = $locale;
                    DB::table($model->getTranslationTable())->insert($transData);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $modelWithTrans = self::find($model->{$model->getKeyName()});

        if (method_exists(self::class, 'revisionableCreate')) {
            $modelWithTrans->revisionableCreate('created');
        }

        return $modelWithTrans;
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function updateTranslated(array $data)
    {
        $transData = [];
        foreach ($this->getTranslationAttributes() as $translationAttribute) {
            if (array_key_exists($translationAttribute, $data)) {
                $transData[$translationAttribute] = $data[$translationAttribute];
                unset($data[$translationAttribute]);
            }
        }

        DB::beginTransaction();

        try {
            $this->update($data);

            if (!empty($transData)) {
                DB::table($this->getTranslationTable())
                    ->updateOrInsert(
                        [
                            $this-> getTranslationRelationField() => $this->id,
                            $this-> getTranslationLocaleField() => Auth::check() ? Auth::user()->locale : app()->getLocale()
                        ],
                        $transData
                    );

                foreach ($transData as $property => $value) {
                    $this->$property = $value;
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        if (method_exists(self::class, 'revisionableCreate')) {
            $this->revisionableCreate('updated');
        }

        $this->original = $this->attributes;
    }
}
