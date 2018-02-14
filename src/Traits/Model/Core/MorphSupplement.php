<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use More\Laravel\Util;

/**
 * Trait SearchesMorphs
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder whereMorphedBy(Model|string $model, string $morph = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorph(Model $model, string $morph = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorphNull(string $morph = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereMorphNotNull(string $morph = null)
 */
trait MorphSupplement
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string|null $related_field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereModel($query, Model $model, $related_field = null)
    {
        $model_class = get_class($model);
        $model_id = $model->getKey();

        if (empty($related_field)) {
            $related_field = array_last(explode('\\', $model_class));
            $related_field = strtolower(Str::snake(Str::singular($related_field)));
        }

        return $query->where("{$related_field}_id", $model_id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param Model|string $model_or_class
     * @param string|null $morph
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMorphedBy($query, $model_or_class, $morph = null)
    {
        $model_class = is_object($model_or_class)
            ? get_class($model_or_class)
            : $model_or_class;

        if (empty($morph)) {
            $morph = array_last(explode('\\', $model_class));
            $morph = strtolower(Str::snake(Str::singular($morph)));
        }

        return $query->where("{$morph}_type", Util::rawClass($model_class));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|MorphSupplement $query
     * @param Model $model
     * @param string|null $morph
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMorph($query, Model $model, $morph = null)
    {
        $model_class = get_class($model);
        $model_id = $model->getKey();

        if (empty($morph)) {
            $morph = strtolower(Str::snake(Str::singular(class_basename($morph))));
        }

        return $query->where("{$morph}_type", Util::rawClass($model_class))
            ->where("{$morph}_id", $model_id);
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
        $model_class = get_class($this);

        if (is_null($morph)) {
            $morph = strtolower(Str::snake(Str::singular(class_basename($model_class))));
        }

        return [
            "{$morph}_type" => $model_class,
            "{$morph}_id" => $this->getKey()
        ];
    }
}
