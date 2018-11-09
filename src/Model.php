<?php

namespace More\Laravel;

use More\Laravel\Traits\Model\Core\EventSupplement;
use More\Laravel\Traits\Model\Core\GroupsOnce;
use More\Laravel\Traits\Model\Core\JoinsOnce;
use More\Laravel\Traits\Model\Core\MorphSupplement;
use More\Laravel\Traits\Model\Core\AttributeSupplement;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Class Model
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|BaseModel
 */
abstract class Model extends BaseModel
{
    use AttributeSupplement,
        GroupsOnce,
        JoinsOnce,
        MorphSupplement,
        EventSupplement;
}
