<?php

namespace More\Laravel\Traits\Model;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait CacheLock
 *
 * @mixin \App\Model|\More\Laravel\Model|Model
 */
trait CacheLock
{
    /** @var int $cache_lock_expires_in_secs */
    public static $cache_lock_expires_in_secs = 60 * 60;

    /**
     * @param string $appends
     * @return bool
     */
    public function hasLock(string $appends = '')
    {
        return Cache::has($this->getCacheLockKey($appends));
    }

    /**
     * @param string $appends
     */
    public function lock(string $appends = '')
    {
        $seconds = $this->getCacheLockSeconds($appends);
        Cache::put($this->getCacheLockKey($appends), 1, $seconds);
    }

    /**
     * @param string $appends
     */
    public function unlock(string $appends = '')
    {
        Cache::forget($this->getCacheLockKey($appends));
    }

    /**
     * @param $appends
     * @return integer|null
     */
    public function getCacheLockSeconds($appends)
    {
        $base = Str::snake(class_basename($this));

        return config("lock.$base.expires.$appends", static::$cache_lock_expires_in_secs);
    }

    /**
     * @param string $appends
     * @return string
     */
    private function getCacheLockKey($appends = '')
    {
        $base = Str::snake(class_basename($this));

        return strlen($appends)
            ? "$base|$appends|{$this->getKey()}"
            : "$base|{$this->getKey()}";
    }
}