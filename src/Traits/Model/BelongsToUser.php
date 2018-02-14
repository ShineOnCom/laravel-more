<?php

namespace More\Laravel\Traits\Model;

use App\User;
use Illuminate\Support\Collection;

/**
 * Class BelongsToUser
 *
 * For any model / table with a `user_id` column that "belongs to user"
 *
 * @mixin \Eloquent
 * @property User $user
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder forUser(User $user)
 * @method static \Illuminate\Database\Eloquent\Builder forUsers($users)
 */
trait BelongsToUser
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, User $user)
    {
        $user_field = sprintf('%s.user_id', $this->getTable());

        return $query->where($user_field, $user->id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection|array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUsers($query, $users)
    {
        $users = is_object($users) && $users instanceof Collection
            ? $users : collect($users);

        $uids = $users->pluck('id')->all();
        $user_field = sprintf('%s.user_id', $this->getTable());

        return $query->whereIn($user_field, $uids);
    }
}
