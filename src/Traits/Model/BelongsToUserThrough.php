<?php

namespace More\Laravel\Traits\Model;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use More\Laravel\Util;

/**
 * Class BelongsToUserThrough
 *
 * For any model / table that is related to the user table through an
 * intermediary model with a `user_id` column that "belongs to user"
 *
 * @mixin \Eloquent
 * @property User $user
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder forUser(User $user)
 * @method static \Illuminate\Database\Eloquent\Builder forUsers($users)
 */
trait BelongsToUserThrough
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
        $class = $relation ?: static::BELONGS_TO_USER_THROUGH;

        $relation = Util::guessSingularRelation($class);

        return $this->$relation->id == $user->getKey();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection|array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUsers($query, $users)
    {
        $class = static::BELONGS_TO_USER_THROUGH;

        $table = (new $class)->getTable();

        $relation = Util::guessSingularRelation($class);

        $users = is_object($users) && $users instanceof Collection
            ? $users : collect($users);

        $uids = $users->pluck('id')->all();

        $user_field = sprintf('%s.user_id', $this->getTable());

        return $query
            ->join($table, $table.'.'.$relation.'_id', '=', $this->getTable().'.id')
            ->whereIn($user_field, $uids);
    }
}
