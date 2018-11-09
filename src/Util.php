<?php

namespace More\Laravel;

use DB;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Util
 */
class Util
{
    /**
     * @param object|string $model
     * @param string $postfix
     * @return string
     */
    public static function field($model, $postfix = '')
    {
        $field = Str::snake(Str::singular(class_basename($model)));

        return implode('_', array_filter([$field, $postfix]));
    }

    /**
     * @param $class
     * @param string $quote
     * @return Expression
     */
    public static function rawClass($class, $quote = "'")
    {
        $class = is_object($class) ? get_class($class) : $class;
        $class = addslashes($class);

        return DB::raw($quote.$class.$quote);
    }

    /**
     * @param \App\Model|\More\Laravel\Model|\Eloquent|Model $class
     * @return string
     */
    public static function guessSingularRelation($class)
    {
        /** @var \Illuminate\Database\Eloquent\Model $class */
        $class = is_object($class) ? $class : (new $class);

        return Str::singular($class->getTable());
    }
}