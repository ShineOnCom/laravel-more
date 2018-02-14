<?php

namespace More\Laravel\Traits\Model;

use App\Model;

/**
 * Trait BelongsToParent
 *
 * @mixin \Eloquent
 * @method static forParents() \Illuminate\Database\Eloquent\Builder
 * @method static forChildren() \Illuminate\Database\Eloquent\Builder
 * @property Model parent
 */
trait BelongsToParent
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        /** @var Model $this */
        return $this->belongsTo(get_class($this), 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        /** @var Model $this */
        return $this->hasMany(get_class($this), 'parent_id', 'id');
    }

    /**
     * @param bool $include_self
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function siblings($include_self = false)
    {
        return $include_self
            ? $this->children()
            : $this->children()
                ->where(sprintf('%s.id', $this->getTable()), '!=', $this->getKey());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForParents($query)
    {
        return $query->whereNull(sprintf('%s.parent_id', $this->getTable()));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForChildren($query)
    {
        return $query->whereNotNull(sprintf('%s.parent_id', $this->getTable()));
    }
}
