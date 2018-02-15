<?php

namespace More\Laravel\Traits\Model\Core;

use Illuminate\Database\Eloquent\Model;
use More\Laravel\Util;

/**
 * Trait AttributeSupplement
 *
 * @mixin \Eloquent
 */
trait AttributeSupplement
{
    /**
     * @param null $as
     * @return array
     */
    public function compact($as = null)
    {
        if (is_null($as)) {
            $as = Util::field($this);
        }

        return [$as.'_id' => $this->getKey()];
    }

    /**
     * Set an individual model attribute. No checking is done.
     *
     * @param  string  $key
     * @param  mixed $value
     * @param  bool  $sync
     * @return $this
     */
    public function setRawAttribute($key, $value, $sync = false)
    {
        $this->attributes[$key] = $value;

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Add mutated attributes to a model before sending it back in a response.
     *
     * @param array ...$args
     * @return Model
     */
    public function withAttributes(...$args)
    {
        collect($args)->flatten()->each(function($mutator) {
            $this->attributes[$mutator] = $this->$mutator;
        });

        return $this;
    }
}
