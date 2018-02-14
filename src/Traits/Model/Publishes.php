<?php

namespace More\Laravel\Traits\Model;

use Carbon\Carbon;

/**
 * Trait Publishes
 *
 * Don't forget to add `published_at` to your $casts or $dates property.
 *
 * @mixin \Eloquent
 * @method static forPublished() \Illuminate\Database\Eloquent\Builder
 * @method static forNotPublished() \Illuminate\Database\Eloquent\Builder
 * @property \Carbon\Carbon published_at
 */
trait Publishes
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPublished($query)
    {
        return $query->whereNotNull(sprintf('%s.published_at', $this->getTable()));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForNotPublished($query)
    {
        return $query->whereNull(sprintf('%s.published_at', $this->getTable()));
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return ! empty($this->published_at);
    }

    /**
     * @return bool
     */
    public function publish()
    {
        $this->published_at = new Carbon();

        return $this->save();
    }

    /**
     * @return bool
     */
    public function unpublish()
    {
        $this->published_at = null;

        return $this->save();
    }
}
