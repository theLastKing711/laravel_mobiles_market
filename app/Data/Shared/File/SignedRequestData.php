<?php

namespace App\Data\Shared\File;

use App\Enum\FileUploadDirectory;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class SignedRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $timestamp,
        #[OAT\Property]
        public string $eager,
        #[OAT\Property]
        public FileUploadDirectory $folder,
        #[OAT\Property]
        public string $signature,
        #[OAT\Property]
        public string $api_key,
        #[OAT\Property]
        public string $cloud_name,
    ) {}

}
