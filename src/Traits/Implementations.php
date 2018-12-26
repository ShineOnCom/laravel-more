<?php

namespace More\Laravel\Traits;

/**
 * Trait Implementations
 *
 * It's true. Some may say it's sad this Trait exists. But it'll ease the
 * refactor to a variety of different stores that implement a variety of
 * platform features. ¯\_(ツ)_/¯
 */
trait Implementations
{
    /**
     * @param mixed ...$classes
     * @return bool
     */
    public function hasImplementation(...$classes)
    {
        return count(array_diff($classes, class_implements($this))) == 0;
    }

    /**
     * @param mixed ...$classes
     * @return bool
     */
    public function hasAnyImplementation(...$classes)
    {
        return count(array_intersect($classes, class_implements($this))) > 0;
    }

    /**
     * @param mixed ...$classes
     * @return bool
     */
    public function hasTrait(...$classes)
    {
        return count(array_diff($classes, class_uses($this))) == 0;
    }

    /**
     * @param mixed ...$classes
     * @return bool
     */
    public function hasAnyTrait(...$classes)
    {
        return count(array_intersect($classes, class_uses($this))) > 0;
    }

    /**
     * @param mixed ...$classes
     * @return bool
     */
    public function hasTraitRecursive(...$classes)
    {
        return count(array_diff($classes, class_uses_recursive($this))) == 0;
    }

    /**
     * @param mixed ...$classes
     * @return bool
     */
    public function hasAnyTraitRecursive(...$classes)
    {
        return count(array_intersect($classes, class_uses_recursive($this))) > 0;
    }
}