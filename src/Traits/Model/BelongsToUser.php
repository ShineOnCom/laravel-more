<?php

namespace More\Laravel\Traits\Model;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection as BaseCollection;

/**
 * Class BelongsToUser
 *
 * For any model / table with a `user_id` column that "belongs to user"
 *
 * @mixin  \App\Model|\More\Laravel\Model|\Eloquent|Model
 * @property User $user
 * @property int $user_id
 * @method static Builder forUser(User $user)
 * @method static Builder forUsers($users)
 */
trait BelongsToUser
{
    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param User $user
     * @param array $attributes
     * @return static
     */
    public static function createForUser(User $user, array $attributes = [])
    {
        return static::create(['user_id' => $user->getKey()] + $attributes);
    }

    /**
     * @param User $user
     * @param array $attributes
     * @return static
     */
    public static function fillForUser(User $user, array $attributes = [])
    {
        return new static(['user_id' => $user->getKey()] + $attributes);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAccessibleBy(User $user)
    {
        return $this->user_id == $user->getKey();
    }

    /**
     * @param static|Builder $query
     * @param User $user
     * @return static|Builder
     */
    public function scopeForUser($query, User $user)
    {
        $user_field = "{$this->getTable()}.user_id";

        return $query->where($user_field, $user->id);
    }

    /**
     * @param static|Builder $query
     * @param BaseCollection|array
     * @return static|Builder
     */
    public function scopeForUsers($query, $users)
    {
        $users = is_object($users) && $users instanceof BaseCollection
            ? $users : collect($users);

        $uids = $users->pluck('id')->all();
        $user_field = "{$this->getTable()}.user_id";

        return $query->whereIn($user_field, $uids);
    }
}
