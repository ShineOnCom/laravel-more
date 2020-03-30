<?php

namespace More\Laravel\Traits\Console;

/**
 * Trait IdHelper
 *
 * For classes that extend \Illuminate\Console\Command
 *
 * @deprecated
 * @mixin \Illuminate\Console\Command
 */
trait IdHelper
{
    /**
     * @param $arg
     * @return array|null
     */
    public function optionIds($arg)
    {
        $option = ($this->option($arg));

        if (is_null($option) || $option == 'all' || $option == 'any') {
            return null;
        }

        $ids = explode(',', $option);
        $ids = array_map('trim', $ids);
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids);

        return empty($ids) ? null : $ids;
    }
}
