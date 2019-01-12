<?php

namespace More\Laravel\Traits\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Trait VerbosityHelper
 *
 * @mixin Command
 */
trait VerbosityHelper
{
    /**
     * @param int $level
     * @return bool
     */
    public function v($level = OutputInterface::VERBOSITY_VERBOSE)
    {
        return $this->getOutput()->getVerbosity() % $level == 0;
    }
}