<?php

namespace App\Services\Api;

use App\Data\Shared\TranslationSetData;
use App\Enum\Language;
use Datlechin\GoogleTranslate\Facades\GoogleTranslate;

class TranslationService
{
    public function translateFromArabicToEnglish(string $text): string
    {
        $result = GoogleTranslate::source('ar')
            ->target('en')
            ->translate($text);

        return $result->getTranslatedText();

    }

    public function translateFromEnglishToArabic(string $text): string
    {
        $result = GoogleTranslate::source('en')
            ->target('ar')
            ->translate($text);

        return $result->getTranslatedText();

    }

    public function translate(string $text)
    {
        if ($this->isEnglishLetter($text[0])) {
            return new TranslationSetData(
                name_ar: $this->translateFromEnglishToArabic($text),
                name_en: $text,
                upload_language: Language::EN
            );
        }

        return new TranslationSetData(
            name_ar: $text,
            name_en: $this->translateFromArabicToEnglish($text),
            upload_language: Language::AR

        );
    }

    private function isEnglishLetter(string $text)
    {
        return ! preg_match('/[^A-Za-z0-9]/', $text) ? true : false;
    }
}
