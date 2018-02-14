<?php

namespace More\Laravel\Traits;

/**
 * Trait Makeable
 */
trait Makeable
{
    /**
     * @param array $parameters
     * @return \Illuminate\Foundation\Application|mixed
     */
    public static function make(array $parameters = [])
    {
        return app(static::class, $parameters);
    }
}
