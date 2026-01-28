<?php

namespace App\Data\Shared\File;

use App\Models\TemporaryUploadedImages;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class TemporaryUploadedImagesData extends Data
{
    public function __construct(
        #[
            OAT\Property,
            Exists(TemporaryUploadedImages::class, 'id')
        ]
        public int $id,
    ) {}

}
