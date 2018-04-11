<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Model;
use More\Laravel\Util;

/**
 * Trait SearchesMorphs
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder whereModel(Model $model, string $related_field = null, $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder whereNotModel(Model $model, string $related_field = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorphedBy(Model|string $model, string $morph = null, $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder whereNotMorphedBy(Model|string $model, string $morph = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorph(Model $model, string $morph = null, $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder whereNotMorph(Model $model, string $morph = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorphNull(string $morph = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorphNotNull(string $morph = null)
 */
trait MorphSupplement
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string|null $related_field
     * @param string $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereModel($query, Model $model, $related_field = null, $operator = '=')
    {
        if (empty($related_field)) {
            $related_field = Util::field($model);
        }

        return $query->where("{$related_field}_id", $operator, $model->getKey());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string|null $related_field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotModel($query, Model $model, $related_field = null)
    {
        return $this->whereModel($query, $model, $related_field, '!=');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param Model|string $model
     * @param string|null $morph
     * @param string $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMorphedBy($query, $model, $morph = null, $operator = '=')
    {
        if (empty($morph)) {
            $morph = Util::field($model);
        }

        return $query->where("{$morph}_type", $operator, Util::rawClass($model));
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param Model|string $model
     * @param string|null $morph
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotMorphedBy($query, $model, $morph = null)
    {
        return $this->whereMorphedBy($query, $model, $morph, '!=');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param Model $model
     * @param string|null $morph
     * @param string $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMorph($query, Model $model, $morph = null, $operator = '=')
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
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param Model $model
     * @param string|null $morph
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotMorph($query, Model $model, $morph = null)
    {
        return $this->whereMorph($query, $model, $morph, '!=');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param null $morph
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMorphNull($query, $morph)
    {
        return $query->whereNull("{$morph}_type")
            ->whereNull("{$morph}_id");
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param null $morph
     * @return \Illuminate\Database\Eloquent\Builder
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
