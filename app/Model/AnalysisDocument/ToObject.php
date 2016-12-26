<?php

namespace App\Model\AnalysisDocument;

class ToObject
{
    private $base;

    protected function __construct($base)
    {
        $this->base = $base;
    }

    /**
     *
     */
    public function __get(string $key)
    {
        return $this->base[$key] ?? null;
    }
}
