<?php

namespace More\Laravel;

use DB;

/**
 * Class Util
 */
class Util
{
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
}