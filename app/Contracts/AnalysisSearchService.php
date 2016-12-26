<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface AnalysisSearchService
{
    public function store(int $id, $document): void;
    public function search(string $query): Collection;
}
