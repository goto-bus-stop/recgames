<?php

namespace App\Services;

use Illuminate\Support\Collection;

trait DefaultGetManyTrait
{
    public function getMany(array $ids): Collection
    {
        return collect($ids)->map([&$this, 'get']);
    }
}
