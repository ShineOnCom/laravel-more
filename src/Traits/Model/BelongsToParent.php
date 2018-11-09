<?php

namespace More\Laravel\Traits\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait BelongsToParent
 *
 * @mixin  \App\Model||\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder forParents()
 * @method static static|Builder forChildren()
 * @property Collection $children
 * @property  \App\Model||\More\Laravel\Model|\Eloquent|Model|static $parent
 * @property Collection $siblings
 */
trait BelongsToParent
{
    /**
     * @return BelongsTo
     */
    public function parent()
    {
        /** @var Model $this */
        return $this->belongsTo(get_class($this), 'parent_id', 'id');
    }

    /**
     * Direct children, does not include grandchildren
     *
     * @return HasMany|static
     */
    public function children()
    {
        /** @var Model $this */
        return $this->hasMany(get_class($this), 'parent_id', 'id');
    }

    /**
     * @param bool $include_self
     * @return HasMany|Builder
     */
    public function siblings($include_self = false)
    {
        // If the model is a parent with no ancestors
        if (empty($this->parent_id)) {
            $hasMany = $this->hasMany(get_class($this), 'parent_id');
            $hasMany->getBaseQuery()->wheres = [];

            // Then return other parents with no ancestors
            return $hasMany->whereNull('parent_id')
                ->when(! $include_self, function($query) {
                    /** @var Builder $query */
                    $query->where("{$this->getTable()}.id",'!=', $this->getKey());
                });
        // Otherwise, do what you would expect
        } else {
            return $this->parent->children()
                ->when(! $include_self, function($query) {
                    /** @var Builder $query */
                    $query->where("{$this->getTable()}.id",'!=', $this->getKey());
                });
        }
    }

    /**
     * @param Builder $query
     * @return static|Builder
     */
    public function scopeForParents($query)
    {
        return $query->whereNull("{$this->getTable()}.parent_id");
    }

    /**
     * @param Builder $query
     * @return static|Builder
     */
    public function scopeForChildren($query)
    {
        return $query->whereNotNull("{$this->getTable()}.parent_id");
    }
}
