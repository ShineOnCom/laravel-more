<?php

namespace More\Laravel\Events\Model;

use Illuminate\Database\Eloquent\Model;
use More\Laravel\Traits\Model\CacheLock;

/**
 * Class Lock
 */
class Lock extends Unlock
{
    /** @var int $minutes*/
    protected $minutes;

    /**
     * Installed constructor.
     *
     * @param \App\Model|\More\Laravel\Model|\Eloquent|Model|CacheLock $model
     * @param int $minutes
     * @param string $appends
     */
    public function __construct($model, string $appends = '', int $minutes = null)
    {
        parent::__construct($model, $appends);
        $this->minutes = $minutes ?: $model->getCacheLockSeconds($appends);
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->minutes;
    }
}
