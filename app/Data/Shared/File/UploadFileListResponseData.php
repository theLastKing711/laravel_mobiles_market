<?php

namespace App\Data\Shared\File;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class UploadFileListResponseData extends Data
{
    /** @param UploadFileResponseData[] $files */
    public function __construct(
        #[ArrayProperty(UploadFileResponseData::class)]
        /** @var UploadFileResponseData[] */
        public Collection $files,
    ) {}
}
