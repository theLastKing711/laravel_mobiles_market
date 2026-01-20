<?php

namespace App\Enum;

use Illuminate\Support\Str as SupportStr;

enum Language: int
{
    case AR = 1;
    case EN = 2;

    public static function getWordLanguage(string $string)
    {
        if (SupportStr::length($string) === 0) {
            return self::EN;
        }

        if (! preg_match('/[^A-Za-z0-9]/', $string[0])) {
            // The string contains only English letters and digits
            return self::EN;
        } else {
            // The string contains non-English characters
            return self::AR;
        }
    }
}
