<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

use App\Model\AnalysisDocument\Document;
use App\Contracts\AnalysisStorageService;

class FilesystemStorageService implements AnalysisStorageService
{
    use DefaultGetManyTrait;

    private $fs;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs->disk('analysis_documents');
    }

    public function store(int $id, $document): void
    {
        $this->fs->put($id . '.json', json_encode($document, JSON_UNESCAPED_SLASHES));
    }

    public function get(int $id): Document
    {
        $source = $this->fs->get($id . '.json');
        if (empty($source)) {
            throw new \Exception('Could not find analysis #' . $id);
        }

        $arr = json_decode($source, true);

        return Document::hydrate($arr);
    }
}
