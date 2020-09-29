<?php

if (! function_exists('sql')) {
    function sql($query)
    {
        $bindings = array_map(static function ($value) {
            return is_string($value) ? "'".str_replace('\\', '\\\\', $value)."'" : $value;
        }, $query->getBindings());

        $segments = explode('?', $query->toSql());

        $result = array_shift($segments);

        foreach ($segments as $segment) {
            $result .= (array_shift($bindings) ?? '?').$segment;
        }

        return $result;
    }
}

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
