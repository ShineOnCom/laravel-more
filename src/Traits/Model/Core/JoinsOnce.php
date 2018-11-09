<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait JoinsOnce
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder joinExists(string $table)
 * @method static static|Builder groupByExists(string $field)
 * @method static static|Builder joinOnce(string $table, string $first, string $operator, string $second, string $type = 'inner', bool $where = false)
 * @method static static|Builder leftJoinOnce(string $table, string $first, string $operator, string $second)
 * @method static static|Builder rightJoinOnce(string $table, string $first, string $operator, string $second)
 */
trait JoinsOnce
{
    /**
     * @param static|Builder $query
     * @param \App\Model|\More\Laravel\Model|\Eloquent|Model|string $table
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
            if ((string) $j->table == (string) $table) {
                return true;
            }
        }

        return false;
    }

    /**
     * Safely add a join clause to the query.
     *
     * @param static|Builder $query
     * @param  string  $table
     * @param  string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @param  string  $type
     * @param  bool    $where
     * @return static|Builder
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
     * @param static|Builder $query
     * @param  string  $table
     * @param  string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @return static|Builder
     */
    public function scopeLeftJoinOnce($query, $table, $first, $operator = null, $second = null)
    {
        return $query->joinOnce($table, $first, $operator, $second, 'left');
    }

    /**
     * Safely add a right join to the query.
     *
     * @param static|Builder $query
     * @param  string  $table
     * @param  string  $first
     * @param  string  $operator
     * @param  string  $second
     * @return static|Builder
     */
    public function scopeRightJoinOnce($query, $table, $first, $operator = null, $second = null)
    {
        return $query->joinOnce($table, $first, $operator, $second, 'right');
    }
}
