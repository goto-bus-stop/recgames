<?php

namespace App;

trait SlugableTrait
{
    public function generatedSlug()
    {
        while (!$this->slug || static::where('slug', $this->slug)->count() > 0) {
            $this->slug = str_random(6);
        }
        return $this;
    }

    /**
     *
     */
    public static function fromSlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}
