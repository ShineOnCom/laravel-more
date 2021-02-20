<?php

namespace More\Laravel\Traits\Model\User;

/**
 * Trait AbbreviatesNames
 *
 * @mixin  \App\Model||\More\Laravel\Model|\Eloquent|Model
 * @property string $first_name
 * @property string $last_name
 */
trait AbbreviatesNames
{
    /**
     * @param string $name
     */
    public function setNameAttribute($name)
    {
        $name = explode(' ', $name);
        $this->attributes['first_name'] = array_shift($name);
        $this->attributes['last_name'] = implode(' ', $name);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * @return string
     */
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->first_name, 0, 1)
            .substr($this->last_name, 0, 1));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|string|static[]
     */
    public function getFirstLastInitialAttribute()
    {
        $name = $this->first_name;

        if (! empty($this->last_name)) {
            $name .= " ".substr($this->last_name, 0, 1).".";
        }

        return $name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|string|static[]
     */
    public function getFirstInitialLastAttribute()
    {
        if (! empty($this->first_name)) {
            $name = substr($this->first_name, 0, 1).".";
        } else {
            $name = "";
        }

        if (! empty($this->last_name)) {
            $name .= $this->last_name;
        }

        return $name;
    }
}
