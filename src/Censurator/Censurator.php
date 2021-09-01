<?php

namespace App\Censurator;

use App\Entity\Wish;

class Censurator
{
    const LIST_MOT_CENSURER = [
        "connard",
        "salope",
        "enculer",
    ];

    public function purify(string $text): string
    {
        foreach (self::LIST_MOT_CENSURER as $unwantedWord) {
            $replacement = str_repeat("*", mb_strlen($unwantedWord));
            $text = str_ireplace($unwantedWord, $replacement, $text);
        }
        return $text;
    }
}