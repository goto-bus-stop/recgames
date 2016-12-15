<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\Collection;

use App\Model\AnalysisDocument\Document;
use App\Contracts\AnalysisStorageService;

class ElasticSearchService implements AnalysisStorageService
{
    private $es;

    public function __construct()
    {
        $this->es = ClientBuilder::create()->build();
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

    public function get(int $id): Document
    {
        $response = $this->es->get([
            'index' => 'recgames',
            'type' => 'analyses',
            'id' => $id,
        ]);

        if (empty($response)) {
            throw new \Exception('Could not find analysis #' . $id);
        }

        return Document::hydrate($response['_source']);
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

        return collect($results['hits']['hits'])->map(function (array $item): Document {
            $doc = Document::hydrate($item['_source']);

            $doc->meta = new \StdClass;
            $doc->meta->score = $item['_score'];
            $doc->meta->analysis_id = (int) $item['_id'];

            return $doc;
        });
    }
}
