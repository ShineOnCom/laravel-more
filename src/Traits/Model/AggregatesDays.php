<?php

namespace More\Laravel\Traits\Model;

use DateTime;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait AggregatesDays
 *
 * Easily add stats to an entity for your Dashboard widgets.
 *
 * @mixin  \App\Model||\More\Laravel\Model|\Eloquent|Model
 * @method static static|Builder forDayCount(string $at = null)
 * @method static static|Builder forDaySum(string $field, string $at = null)
 * @method static static|Builder forDayRaw(string|array $expression, string $at = null)
 */
trait AggregatesDays
{
    /** @var array $_aggregates_days_labels */
    public static $_aggregates_days_labels = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        '7day' => '7 previous days',
        '30day' => '30 previous days',
        '90day' => '90 previous days',
        'all_time' => 'All time',
    ];

    /** @var string $_aggregates_days_timestamp */
    public static $_aggregates_days_timestamp = '%s.created_at';

    /**
     * @param static|Builder $query
     * @param string|null $at
     * @return array
     */
    public function scopeForDayCount($query, $at = null)
    {
        $at = $at ?: $this->getAggregatesDaysAt();
        
        $lower = (new DateTime('now'))->format('Y-m-d 00:00:00');
        
        $data['today'] = (clone $query)
            ->where($at, '>=', $lower)
            ->count();

        $lower = (new DateTime('yesterday'))->format('Y-m-d 00:00:00');
        $upper = (new DateTime('yesterday'))->format('Y-m-d 23:59:59');
        $data['yesterday'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->count();

        $lower = (new DateTime('-8 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['7day'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->count();

        $lower = (new DateTime('-30 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['30day'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->count();

        $lower = (new DateTime('-90 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['90day'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->count();

        $data['all_time'] = (clone $query)
            ->count();

        return $data;
    }

    /**
     * @param static|Builder $query
     * @param string $field
     * @param string|null $at
     * @return array
     */
    public function scopeForDaySum($query, $field, $at = null)
    {
        $at = $at ?: $this->getAggregatesDaysAt();
        
        $lower = (new DateTime('now'))->format('Y-m-d 00:00:00');
        
        $data['today'] = (clone $query)
            ->where($at, '>=', $lower)
            ->sum($field);

        $lower = (new DateTime('yesterday'))->format('Y-m-d 00:00:00');
        $upper = (new DateTime('yesterday'))->format('Y-m-d 23:59:59');
        $data['yesterday'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->sum($field);

        $lower = (new DateTime('-8 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['7day'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->sum($field);

        $lower = (new DateTime('-30 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['30day'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->sum($field);

        $lower = (new DateTime('-90 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['90day'] = (clone $query)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->sum($field);

        $data['all_time'] = (clone $query)
            ->sum($field);

        return $data;
    }

    /**
     * Provide an array of string, array of Expression or string or Expression
     *
     * @param static|Builder
     * @param array|string $select
     * @param string|null $at
     * @return array
     */
    public function scopeForDayRaw($query, $select, $at = 'created_at')
    {
        $at = $at ?: $this->getAggregatesDaysAt();
        
        if (is_array($select)) {
            $select = array_map(function($raw) {
                return is_string($raw) ? DB::raw($raw) : $raw;
            }, $select);
        } elseif (is_string($select)) {
            $select = DB::raw($select);
        }

        $lower = (new DateTime('now'))->format('Y-m-d 00:00:00');
        $data['today'] = (clone $query)
            ->select($select)
            ->where($at, '>=', $lower)
            ->get()
            ->map(function(Model $m) { return $m->toArray(); })
            ->first();

        $lower = (new DateTime('yesterday'))->format('Y-m-d 00:00:00');
        $upper = (new DateTime('yesterday'))->format('Y-m-d 23:59:59');
        $data['yesterday'] = (clone $query)
            ->select($select)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->get()
            ->map(function(Model $m) { return $m->toArray(); })
            ->first();

        $lower = (new DateTime('-8 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['7day'] = (clone $query)
            ->select($select)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->get()
            ->map(function(Model $m) { return $m->toArray(); })
            ->first();

        $lower = (new DateTime('-30 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['30day'] = (clone $query)
            ->select($select)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->get()
            ->map(function(Model $m) { return $m->toArray(); })
            ->first();

        $lower = (new DateTime('-90 days'))->format("Y-m-d 00:00:00");
        $upper = (new DateTime('-1 day'))->format("Y-m-d 23:59:59");
        $data['90day'] = (clone $query)
            ->select($select)
            ->where($at, '>=', $lower)
            ->where($at, '<=', $upper)
            ->get()
            ->map(function(Model $m) { return $m->toArray(); })
            ->first();

        $data['all_time'] = (clone $query)
            ->select($select)
            ->get()
            ->map(function(Model $m) { return $m->toArray(); })
            ->first();

        return $data;
    }

    /**
     * @return string
     */
    public function getAggregatesDaysAt()
    {
        $table = $this->getTable();

        return sprintf(static::$_aggregates_days_timestamp, $table);
    }
}
