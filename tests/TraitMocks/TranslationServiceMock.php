<?php

namespace Tests\TraitMocks;

use App\Enum\Language;
use App\Services\Api\TranslationService;
use Mockery;
use Mockery\MockInterface;

trait TranslationServiceMock
{
    public function mobckTranslateFromArabicToEnglish(string $arabic_word, string $english_translation)
    {
        $this->instance(
            TranslationService::class,
            Mockery::mock(TranslationService::class, function (MockInterface $mock) use ($english_translation, $arabic_word) {
                $mock
                    ->expects('translateFromArabicToEnglish')
                    ->with($arabic_word)
                    ->andReturn(
                        $english_translation,
                    );
            })
                ->makePartial()
        );
    }

    public function mobckTranslateFromEnglishToArabic(string $english_word, string $arabic_translation)
    {
        $this->instance(
            TranslationService::class,
            Mockery::mock(TranslationService::class, function (MockInterface $mock) use ($arabic_translation, $english_word) {
                $mock
                    ->expects('translateFromEnglishToArabic')
                    ->with($english_word)
                    ->andReturn(
                        $arabic_translation,
                    );
            })
                ->makePartial()
        );
    }

    public function mocKTranslate(string $word, string $word_translation)
    {

        if (Language::isEnglishWord($word)) {
            $this
                ->mobckTranslateFromEnglishToArabic(
                    $word,
                    $word_translation
                );

            return;
        }

        $this
            ->mobckTranslateFromArabicToEnglish(
                $word,
                $word_translation
            );

    }
}
