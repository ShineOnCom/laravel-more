<?php

namespace More\Laravel\Traits\Model;

use App\Models\User;
use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use More\Laravel\Util;

/**
 * Class BelongsToUserThrough
 *
 * For any model / table that is related to the user table through an
 * intermediary model with a `user_id` column that "belongs to user"
 *
 * @mixin  Model|\More\Laravel\Model|EloquentModel
 * @property User $user
 * @property int $user_id
 * @method static static|Builder forUser(User $user)
 * @method static static|Builder forUsers($users)
 */
trait BelongsToUserThrough
{
    /**
     * @param null $relation
     * @return BelongsTo
     */
    public function user($relation = null)
    {
        $class = $relation ?: static::BELONGS_TO_USER_THROUGH;

        $relation = Util::guessSingularRelation($class);

        return $this->$relation->belongsTo(User::class);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAccessibleBy(User $user)
    {
        $class = static::BELONGS_TO_USER_THROUGH;

        $relation = Util::guessSingularRelation($class);

        return $this->$relation->id == $user->getKey();
    }

    /**
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeForUser($query, User $user)
    {
        $class = static::BELONGS_TO_USER_THROUGH;

        $table = (new $class)->getTable();

        $relation = Util::guessSingularRelation($class);

        return $query
            ->join($table, $table.'.'.$relation.'_id', '=', $this->getTable().'.id')
            ->where($table.'.user_id', $user->id);
    }

    /**
     * @param Builder $query
     * @param Collection|array
     * @return Builder
     */
    public function scopeForUsers($query, $users)
    {
        $class = static::BELONGS_TO_USER_THROUGH;

        /** @var Model $instance */
        $instance = new $class;

        $table = $instance->getTable();

        $relation = Util::guessSingularRelation($class);

        $users = is_object($users) && $users instanceof Collection
            ? $users : collect($users);

        $uids = $users->pluck('id')->all();

        $user_field = "$table.user_id";

        return $query
            ->join($table, $table.'.'.$relation.'_id', '=', $this->getTable().'.id')
            ->whereIn($user_field, $uids);
    }
}
