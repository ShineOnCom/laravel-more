<?php

namespace More\Laravel\Listeners\Model;

use More\Laravel\Events\Model\Lock;

/**
 * Class LockListener
 */
class LockListener
{
    /**
     * Handle the event.
     *
     * @param Lock $event
     * @return void
     */
    public function handle(Lock $event)
    {
        $event->getModel()->lock($event->getAppends(), $event->getMinutes());
    }
}
