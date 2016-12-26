<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\Collection;

use App\Contracts\AnalysisSearchService;

class ElasticSearchService implements AnalysisSearchService
{
    private $es;

    public function __construct(array $config)
    {
        $this->es = ClientBuilder::fromConfig($config);
    }

    public function store(int $id, $document): void
    {
        $this->es->index([
            'index' => 'recgames',
            'type' => 'analyses',
            'id' => $id,
            'body' => $document,
        ]);
    }

    public function search(string $query): Collection
    {
        $results = $this->es->search([
            'index' => 'recgames',
            'type' => 'analyses',
            'body' => [
                'query' => [
                    'query_string' => ['query' => $query],
                ],
            ],
        ]);

        return collect($results['hits']['hits'])->pluck('_id');
    }
}
