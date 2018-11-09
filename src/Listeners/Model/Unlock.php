<?php

namespace More\Laravel\Listeners\Model;

use More\Laravel\Events\Model\Unlock;

/**
 * Class UnlockListener
 */
class UnlockListener
{
    /**
     * Handle the event.
     *
     * @param Unlock $event
     * @return void
     */
    public function handle(Unlock $event)
    {
        $event->getModel()->unlock($event->getAppends());
    }
}
