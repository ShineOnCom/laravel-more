<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as BaseCollection;
use More\Laravel\Util;

/**
 * Trait SearchesMorphs
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder whereModel($model, string $related_field = null, $operator = '=')
 * @method static static|Builder whereNotModel($model, string $related_field = null)
 * @method static static|Builder whereMorphedBy(Model|string $model, string $morph = null, $operator = '=')
 * @method static static|Builder whereNotMorphedBy(Model|string $model, string $morph = null)
 * @method static static|Builder whereMorph(Model|BaseCollection|array $model_or_collection, string $morph = null, $operator = '=')
 * @method static static|Builder whereMorphIds($model, $ids, string $morph = null, $operator = '=')
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
     * @param Model|Collection|array $model_or_collection
     * @param string|null $morph
     * @param string $operator
     * @return static|Builder
     */
    public function scopeWhereMorph($query, $model_or_collection, $morph = null, $operator = '=')
    {
        if (empty($morph)) {
            $morph = Util::field($model_or_collection);
        }

        // Make a collection with various inputs
        /** @var BaseCollection $collection */
        if (is_object($model_or_collection)) {
            if ($model_or_collection instanceof BaseCollection) {
                $collection = $model_or_collection;
            } elseif ($model_or_collection instanceof Model) {
                $collection = collect([$model_or_collection]);
            }
        } elseif (is_array($model_or_collection)) {
            $collection = collect($model_or_collection);
        } else {
            // Crosses fingers
            $collection = $model_or_collection;
        }

        // No models means no results
        if ($collection->isEmpty()) {
            return $query->whereRaw('1=0');
        }

        $class = get_class($collection->first());
        $keys = $collection->map(function($m) { return $m->getKey(); });

        return $query->whereMorphIds($class, $keys, $morph, $operator);
    }
    
    /**
     * @param static|Builder $query
     * @param Model|string $class
     * @param BaseCollection|array
     * @param string|null $morph
     * @param string $operator
     * @return static|Builder
     */
    public function scopeWhereMorphIds($query, $class, $ids, $morph = null, $operator = '=')
    {
        if (empty($morph)) {
            $morph = Util::field($class);
        }

        // Make array a collection
        if ($ids instanceof BaseCollection) {
            $ids = $ids->all();
        }

        // No models means no results
        if (empty($ids)) {
            return $query->whereRaw('1=0');
        }

        $class = Util::rawClass($class);

        return $query
            ->when($operator == '=',
                function(Builder $q) use ($morph, $class, $ids) {
                    $q->where(function(Builder $together) use ($morph, $class, $ids) {
                        $together->where("{$morph}_type", '=', $class)
                            ->whereIn("{$morph}_id", $ids);
                    });
                },
                // Where not morph class or morph class not in ids
                function(Builder $q) use ($morph, $class, $ids) {
                    $q->where("{$morph}_type", '!=', $class)
                        ->orWhere(function(Builder $or) use ($morph, $class, $ids) {
                            $or->where(function(Builder $together) use ($morph, $class, $ids) {
                                $together->where("{$morph}_type", '=', $class)
                                    ->whereNotIn("{$morph}_id", $ids);
                            });
                        });
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
