<?php

namespace More\Laravel\Traits;

use Illuminate\Foundation\Application;

/**
 * Trait Makeable
 */
trait Makeable
{
    /**
     * @param array $parameters
     * @return Application|mixed
     */
    public static function make(array $parameters = [])
    {
        return app(static::class, $parameters);
    }
}
