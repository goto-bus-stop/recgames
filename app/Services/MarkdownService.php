<?php

namespace App\Services;

use Michelf\MarkdownExtra;
use Highlight\Highlighter;

class MarkdownService
{
    public function transform($source)
    {
        $parser = new MarkdownExtra();
        $parser->empty_element_suffix = '>';

        $parser->url_filter_func = function ($url) {
            // TODO Avoid hardcoding this URL
            if (substr($url, 0, 2) === './') {
                return 'https://github.com/goto-bus-stop/recanalyst/tree/master/' . substr($url, 2);
            }
            return $url;
        };

        $parser->header_id_func = function ($header) {
            return preg_replace('/[^a-z0-9]/', '-', strtolower($header));
        };

        $parser->code_class_prefix = 'hljs ';
        $parser->code_block_content_func = function ($code, $language) {
            if (!$language) {
                return $code;
            }
            return (new Highlighter())->highlight($language, $code)->value;
        };

        return $parser->transform($source);
    }
}
