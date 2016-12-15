<?php

namespace App\Model\AnalysisDocument;

/**
 *
 */
class Document
{
    private $doc;

    public static function hydrate(array $doc)
    {
        return new Document($doc);
    }

    /**
     *
     */
    protected function __construct(array $doc)
    {
        $this->doc = $doc;
    }
}
