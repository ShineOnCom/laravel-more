<?php

namespace More\Laravel\Traits;

use Cache;

/**
 * Trait HasLocales
 *
 * @method static forLocales(array|string $locales) \Illuminate\Database\Eloquent\Builder
 * @property string $primary_locale
 */
trait HasLocales
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getLocales()
    {
        $cache_key = class_basename(get_called_class()).'|locales';

        return Cache::rememberForever($cache_key, function() {
            $dictionary = config('locale.dictionary');

            return static::select('primary_locale')
                ->distinct('primary_locale')
                ->pluck('primary_locale')
                ->filter()
                ->flip()
                ->map(function($nah, $primary_locale) use ($dictionary) {
                    return array_get($dictionary, $primary_locale, $primary_locale);
                });
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|string $locales
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForLocales($query, $locales)
    {
        return $query->whereIn('primary_locale', (array) $locales);
    }
}
