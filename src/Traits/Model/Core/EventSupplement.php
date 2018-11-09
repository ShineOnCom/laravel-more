<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait EventSupplement
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 */
trait EventSupplement
{
    /**
     * @param callable $process
     * @return mixed
     */
    public function withoutEvents(callable $process)
    {
        $temp = $this->getEventDispatcher();

        $this->unsetEventDispatcher();

        $result = $process($this, $temp);

        $this->setEventDispatcher($temp);

        return $result;
    }
}
