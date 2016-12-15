<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

use App\Model\AnalysisDocument\Document;

interface AnalysisStorageService
{
    public function store(int $id, $document): void;
    public function get(int $id): Document;
    public function search(string $query): Collection;
}
