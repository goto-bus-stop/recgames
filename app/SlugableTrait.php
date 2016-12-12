<?php

namespace App;

trait SlugableTrait
{
    /**
     * Sets a unique slug on the model.
     *
     * @return $this
     */
    public function generatedSlug()
    {
        while (!$this->slug || static::where('slug', $this->slug)->count() > 0) {
            $this->slug = str_random(6);
        }
        return $this;
    }

    /**
     * Get a model by its slug.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public static function fromSlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }
}
