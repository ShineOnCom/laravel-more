<?php

namespace More\Laravel\Traits\Model\Core;

/**
 * Trait JoinsOnce
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder joinExists(string $table)
 * @method static \Illuminate\Database\Eloquent\Builder groupByExists(string $field)
 * @method static \Illuminate\Database\Eloquent\Builder joinOnce(string $table, string $first, string $operator, string $second, string $type = 'inner', bool $where = false)
 * @method static \Illuminate\Database\Eloquent\Builder leftJoinOnce(string $table, string $first, string $operator, string $second)
 * @method static \Illuminate\Database\Eloquent\Builder rightJoinOnce(string $table, string $first, string $operator, string $second)
 */
trait JoinsOnce
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder|JoinsOnce $query
     * @param static|\Illuminate\Database\Eloquent\Model|string $table
     * @return bool
     */
    public function scopeJoinExists($query, $table)
    {
        if (is_object($table)) {
            $table = get_class($table);
        } elseif (class_exists($table)) {
            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = new $table;
            /** @var static $table */
            $table = $model->getTable();
        }

        $joins = $query->getQuery()->joins ?: [];

        foreach ($joins as $j) {
            if ($j->table == $table) {
                return true;
            }
        }

        return false;
    }

    /**
     * Safely add a join clause to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|JoinsOnce $query
     * @param  string  $table
     * @param  string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @param  string  $type
     * @param  bool    $where
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinOnce($query, $table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        if ($query->joinExists($table)) {
            return $query;
        }

        return $query->join($table, $first, $operator, $second, $type, $where);
    }

    /**
     * Safely add a left join to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|JoinsOnce $query
     * @param  string  $table
     * @param  string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeLeftJoinOnce($query, $table, $first, $operator = null, $second = null)
    {
        return $query->joinOnce($table, $first, $operator, $second, 'left');
    }

    /**
     * Safely add a right join to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|JoinsOnce $query
     * @param  string  $table
     * @param  string  $first
     * @param  string  $operator
     * @param  string  $second
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeRightJoinOnce($query, $table, $first, $operator = null, $second = null)
    {
        return $query->joinOnce($table, $first, $operator, $second, 'right');
    }
}
