<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use More\Laravel\Util;

/**
 * Trait SearchesMorphs
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder whereModel($model, string $related_field = null, $operator = '=')
 * @method static static|Builder whereNotModel($model, string $related_field = null)
 * @method static static|Builder whereMorphedBy(Model|string $model, string $morph = null, $operator = '=')
 * @method static static|Builder whereNotMorphedBy(Model|string $model, string $morph = null)
 * @method static static|Builder whereMorph($model, string $morph = null, $operator = '=')
 * @method static static|Builder whereNotMorph($model, string $morph = null)
 * @method static static|Builder whereMorphNull(string $morph = null)
 * @method static static|Builder whereMorphNotNull(string $morph = null)
 */
trait MorphSupplement
{
    /**
     * @param static|Builder $query
     * @param Model $model
     * @param string|null $related_field
     * @param string $operator
     * @return static|Builder
     */
    public function scopeWhereModel($query, $model, $related_field = null, $operator = '=')
    {
        if (empty($related_field)) {
            $related_field = Util::field($model);
        }

        return $query->where("{$related_field}_id", $operator, $model->getKey());
    }

    /**
     * @param static|Builder $query
     * @param Model $model
     * @param string|null $related_field
     * @return static|Builder
     */
    public function scopeWhereNotModel($query, $model, $related_field = null)
    {
        return $query->whereModel($query, $model, $related_field, '!=');
    }

    /**
     * @param static|Builder $query
     * @param Model|string $model
     * @param string|null $morph
     * @param string $operator
     * @return static|Builder
     */
    public function scopeWhereMorphedBy($query, $model, $morph = null, $operator = '=')
    {
        if (empty($morph)) {
            $morph = Util::field($model);
        }

        return $query->where("{$morph}_type", $operator, Util::rawClass($model));
    }


    /**
     * @param static|Builder $query
     * @param Model|string $model
     * @param string|null $morph
     * @return static|Builder
     */
    public function scopeWhereNotMorphedBy($query, $model, $morph = null)
    {
        return $query->whereMorphedBy($model, $morph, '!=');
    }

    /**
     * @param static|Builder $query
     * @param Model $model
     * @param string|null $morph
     * @param string $operator
     * @return static|Builder
     */
    public function scopeWhereMorph($query, $model, $morph = null, $operator = '=')
    {
        if (empty($morph)) {
            $morph = Util::field($model);
        }

        return $query
            ->where(function($q) use ($model, $morph, $operator) {
                $q->where("{$morph}_type", $operator, Util::rawClass($model))
                    ->where("{$morph}_id", $operator, $model->getKey());
            });
    }

    /**
     * @param static|Builder $query
     * @param Model $model
     * @param string|null $morph
     * @return static|Builder
     */
    public function scopeWhereNotMorph($query, $model, $morph = null)
    {
        return $query->whereMorph($query, $model, $morph, '!=');
    }

    /**
     * @param static|Builder $query
     * @param null $morph
     * @return static|Builder
     */
    public function scopeWhereMorphNull($query, $morph)
    {
        return $query->whereNull("{$morph}_type")
            ->whereNull("{$morph}_id");
    }

    /**
     * @param static|Builder $query
     * @param null $morph
     * @return static|Builder
     */
    public function scopeWhereMorphNotNull($query, $morph)
    {
        return $query->whereNotNull("{$morph}_type")
            ->whereNotNull("{$morph}_id");
    }

    /**
     * @param null $morph
     * @return array
     */
    public function unmorph($morph = null)
    {
        if (is_null($morph)) {
            $morph = Util::field($this);
        }

        return [
            "{$morph}_type" => get_class($this),
            "{$morph}_id" => $this->getKey()
        ];
    }
}
