<?php

namespace More\Laravel\Traits\Console;

/**
 * Trait LogHelper
 *
 * For classes that extend \Illuminate\Console\Command
 */
trait LogHelper
{
    /**
     * @param $string
     * @param $verbosity
     */
    public function critical($string, $verbosity)
    {
        parent::error("[CRITICAL] $string", $verbosity);
    }

    /**
     * @param $string
     * @param $verbosity
     */
    public function debug($string, $verbosity)
    {
        parent::info("[DEBUG] $string", $verbosity);
    }

    /**
     * @param $string
     * @param null $verbosity
     */
    public function warning($string, $verbosity = null)
    {
        parent::warn($string, $verbosity);
    }

    /**
     * @param $string
     * @param $verbosity
     */
    public function notice($string, $verbosity)
    {
        parent::warn("[NOTICE] $string", $verbosity);
    }

    /**
     * @param $string
     * @param $verbosity
     */
    public function emergency($string, $verbosity)
    {
        parent::error("[EMERGENCY] $string", $verbosity);
    }
}