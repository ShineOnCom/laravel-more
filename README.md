# More (for Laravel)

Why more? Because I seem to use it in all my Laravel projects. And who doesn't want more Laravel? ;p

## Highlights

### compact()

Example for compact(), with say, a payment...

```
$user->update($payment->compact());

// is the same as

$user->update(['payment_id' => $payment->getKey()]);

// or if your field was `default_payment_id` instead of `payment_id`...

$user->update($payment->compact('default_payment');
```

### unmorph($as = null)

The unmorph($as) function is similar to compact()...

```
$order->update($transaction->unmorph('charge'));

// is the same as

$order->update(['charge_id' => $transaction->getKey(), 'charge_type' => get_class($transaction)]);
```

### BelongsToUser

How often do your models belong to `User`... a lot, right?

```
class Post extend Model 
{
    use BelongsToUser;
}
```

#### Usage

```
$post->user()                   // BelongsTo
$post->user                     // App\User
$post->isAccessibleBy($user)    // boolean
Post::forUser($user)            // Builder|Post
Post::forUsers($arr_or_col)     // Builder|Post
Post::createForUser($user)      // Post
```

### And of course there is more included

The `\More\Laravel\Traits\Model\Core` namespace is reasonable for global usage.

The `\More\Laravel\Traits\Model` for the per model use case.

Good Luck!

## Composer

    $ composer require dan/laravel-more dev-master

### Implementation options

#### Do you have a base model? e.g. `App\Model`

> Yes

- Add the traits you like to base model.
- Or extend \More\Laravel\Model

> No

- Make a base model `\App\Model` that extends `More\Laravel\Model` or do à la carte, and `use \More\Laravel\Traits\Model\*` as needed.
- Or extend `\More\Laravel\Model` if you're really lazy.
- Or use traits on specific models...[blerg][1].

## Contributors

- [Diogo Gomes](https://github.com/diogogomeswww)

## Todo

* 

## License

MIT.

[1]: https://www.urbandictionary.com/define.php?term=blerg