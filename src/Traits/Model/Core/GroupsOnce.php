<?php

namespace More\Laravel\Traits\Model\Core;

/**
 * Trait GroupsOnce
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder groupByExists(string $group_by)
 * @method static \Illuminate\Database\Eloquent\Builder groupByOnce(...$groups)
 */
trait GroupsOnce
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|GroupsOnce $query
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
     * @param \Illuminate\Database\Eloquent\Builder|GroupsOnce $query
     * @param  array  ...$groups
     * @return \Illuminate\Database\Eloquent\Builder $query
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
