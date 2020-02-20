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

if (! function_exists('dqt')) {
    /**
     * @param mixed ...$args
     */
    function ddt(...$args)
    {
        $trace = (new Exception(__FUNCTION__))->getTrace();

        array_pop($trace);

        array_push($args, array_first($trace));

        dd($args);
    }
}

if (! function_exists('dqta')) {
    /**
     * @param mixed ...$args
     */
    function ddta(...$args)
    {
        $trace = (new Exception(__FUNCTION__))->getTrace();

        array_pop($trace);

        array_push($args, $trace);

        dd($args);
    }
}
