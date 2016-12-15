<?php

namespace App\Model\AnalysisDocument;

use RecAnalyst\Utils;

class ChatMessage extends ToObject
{
    public static function hydrate(Player $sender = null, array $raw): ChatMessage
    {
        return new ChatMessage($sender, $raw);
    }

    private $sender;

    protected function __construct(Player $sender = null, array $raw)
    {
        parent::__construct($raw);

        $this->sender = $sender;
    }

    public function player(): Player
    {
        return $this->sender;
    }

    public function formattedTime()
    {
        return Utils::formatGameTime($this->time);
    }
}
