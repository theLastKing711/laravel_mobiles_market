<?php

namespace App\Data\Shared\File;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CloudinaryNotificationUrlRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $public_id,
        #[OAT\Property]
        public string $type,
        #[OAT\Property]
        public int $bytes,
        // #[ArrayProperty('string')]
        // /** @var array<string> */
        // public array $tags,
        #[ArrayProperty(CloudinaryEagerUploadData::class)]
        /** @var Collection<CloudinaryEagerUploadData> */
        public Collection $eager,
    ) {}

}
