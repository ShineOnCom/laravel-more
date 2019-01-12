<?php

if (! function_exists('dq')) {
    /**
     * First arg should be a Builder instance or Model.
     *
     * @param mixed ...$args
     */
    function dq(...$args)
    {
        $query = array_shift($args);
        dd($query->toSql(), $query->getQuery()->getBindings(), ...$args);
    }
}

if (! function_exists('dqc')) {
    /**
     * First arg should be a Builder instance or Model.
     *
     * @param mixed ...$args
     */
    function dqc(...$args)
    {
        $query = array_shift($args);
        dd($query->toSql(), $query->getQuery()->getBindings(), (clone $query)->count(), ...$args);
    }
}