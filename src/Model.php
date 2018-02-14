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
 * Please discuss any changes to this Class with Dan before making them.
 * @mixin \Eloquent
 */
abstract class Model extends BaseModel
{
    use AttributeSupplement,
        GroupsOnce,
        JoinsOnce,
        MorphSupplement,
        EventSupplement;
}
