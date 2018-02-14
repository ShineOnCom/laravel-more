<?php

namespace More\Laravel\Traits\Model\Core;

/**
 * Trait EventSupplement
 *
 * @mixin \Eloquent
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
