<?php

namespace More\Laravel\Traits\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Publishes
 *
 * Don't forget to add `published_at` to your $casts or $dates property.
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder forPublished()
 * @method static static|Builder forNotPublished()
 * @property \Carbon\Carbon published_at
 */
trait Publishes
{
    /**
     * @param static|Builder $query
     * @return static|Builder
     */
    public function scopeForPublished($query)
    {
        return $query->whereNotNull("{$this->getTable()}.published_at");
    }

    /**
     * @param static|Builder $query
     * @return static|Builder
     */
    public function scopeForNotPublished($query)
    {
        return $query->whereNull("{$this->getTable()}.published_at");
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
