<?php

namespace App\Data\Shared\File;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class DeleteFileData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $id,
    ) {}
}
