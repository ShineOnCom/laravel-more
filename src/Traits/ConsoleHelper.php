<?php

namespace More\Laravel\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

/**
 * Trait CommandHelper
 *
 * @mixin Command
 */
trait ConsoleHelper
{
    /**
     * @param $arg
     * @param string|callable|null $callable
     * @param array $nullable
     * @return array|null
     */
    public function optionIds($arg, $callable = 'intval', $nullable = ['all', 'any', 'null'])
    {
        $option = $this->option($arg);

        if (in_array($option, $nullable)) {
            return null;
        }

        $ids = explode(',', $option);
        $ids = array_map('trim', $ids);
        if ($callable && is_callable($callable)) {
            $ids = array_map($callable, $ids);
        }
        $ids = array_filter($ids);

        return empty($ids) ? null : $ids;
    }

    /**
     * @param $arg
     * @return Carbon|null
     * @throws Exception
     */
    public function optionDate($arg)
    {
        $option = $this->option($arg);

        if (in_array($option, [null, 'all', 'any', 'null'])) {
            return null;
        }

        return new Carbon($option);
    }

    /**
     * @param string $props
     * @param string $values
     * @param array $default
     * @return array|false
     */
    public function optionCombine($props = 'props', $values = 'values', $default = [])
    {
        $props = $this->optionIds($props, 'trim', $nullable = []) ?: [];
        $values = array_map(function ($v) {
            switch ($v) {
                case 'null':
                    return null;
                case 'false':
                    return false;
                case 'true':
                    return true;
                default:
                    return $v;
            }
        }, $this->optionIds($values, 'trim', $nullable = []) ?: []);

        return array_combine($props, $values);
    }

    /**
     * @param mixed ...$args
     * @return array
     */
    public function compact(...$args)
    {
        return collect($args)
            ->flip()
            ->map(function ($v, $option) {
                return preg_match('/_ids$/', $option)
                    ? $this->optionIds($option)
                    : $this->option($option);
            })
            ->all();
    }
}