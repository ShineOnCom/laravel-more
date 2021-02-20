<?php

namespace More\Laravel\Events\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use More\Laravel\Traits\Model\CacheLock;

/**
 * Class Unlock
 */
class Unlock
{
    use SerializesModels;

    /** @var \App\Model|\More\Laravel\Model|\Eloquent|Model $lock */
    protected $model;

    /** @var string */
    protected $appends;

    /**
     * Installed constructor.
     *
     * @param \App\Model|\More\Laravel\Model|\Eloquent|Model $model
     * @param string $appends
     */
    public function __construct($model, string $appends = '')
    {
        $this->model = $model;
        $this->appends = $appends;
    }

    /**
     * @return string
     */
    public function getAppends(): string
    {
        return $this->appends;
    }

    /**
     * @return \App\Model|\More\Laravel\Model|\Eloquent|Model|CacheLock
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
