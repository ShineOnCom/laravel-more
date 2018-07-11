<?php

namespace More\Laravel;

use DB;
use Illuminate\Support\Str;

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
     * @return \Illuminate\Database\Query\Expression
     */
    public static function rawClass($class, $quote = "'")
    {
        $class = is_object($class) ? get_class($class) : $class;
        $class = addslashes($class);

        return DB::raw($quote.$class.$quote);
    }

    /**
     * @param $class
     */
    public static function guessSingularRelation($class)
    {
        $class = is_object($class) ? $class : (new $class);

        return Str::singular($class->getTable());
    }
}