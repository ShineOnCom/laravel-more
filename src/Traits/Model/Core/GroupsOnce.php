<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait GroupsOnce
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder groupByExists(string $group_by)
 * @method static static|Builder groupByOnce(...$groups)
 */
trait GroupsOnce
{
    /**
     * @param static|Builder $query
     * @param string $group_by
     * @return bool
     */
    public function scopeGroupByExists($query, $group_by)
    {
        $groups = array_map('strtolower', $query->getQuery()->groups ?: []);

        return in_array(strtolower($group_by), $groups);
    }

    /**
     * Add a "group by" clause to the query.
     *
     * @param static|Builder $query
     * @param  array  ...$groups
     * @return static|Builder $query
     */
    public function scopeGroupByOnce($query, ...$groups)
    {
        foreach ($groups as $group) {
            if (! $query->groupByExists($group)) {
                $query->groupBy($group);
            }
        }

        return $query;
    }
}
