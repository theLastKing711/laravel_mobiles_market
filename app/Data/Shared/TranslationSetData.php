<?php

namespace App\Data\Shared;

use App\Enum\Language;
use Spatie\LaravelData\Dto;

class TranslationSetData extends Dto
{
    public function __construct(
        public string $name_en,
        public string $name_ar,
        public Language $upload_language
    ) {}
}
