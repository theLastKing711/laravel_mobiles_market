<?php

namespace App\Data\Shared\File;

use App\Enum\CloudinaryTransformationEnum;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CloudinaryEagerUploadData extends Data
{
    public function __construct(
        #[OAT\Property]
        public CloudinaryTransformationEnum $transformation,
        #[OAT\Property]
        public string $width,
        #[OAT\Property]
        public string $height,
        #[OAT\Property]
        public string $bytes,
        #[OAT\Property]
        public string $format,
        #[OAT\Property]
        public string $url,
        #[OAT\Property]
        public string $secure_url,
    ) {}

}
