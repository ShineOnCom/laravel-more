<?php

namespace More\Laravel\Traits\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Stub
 *
 * Attach to models that are only stubs so data is not persisted to the DB.
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 */
trait Stub
{
    /**
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        return false;
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        return false;
    }
}
