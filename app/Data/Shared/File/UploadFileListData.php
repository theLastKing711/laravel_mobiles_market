<?php

namespace App\Data\Shared\File;

use App\Data\Shared\Casts\ArrayToCollectionCast;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class UploadFileListData extends Data
{
    /** @param Collection<int, UploadedFile> $files */
    public function __construct(
        #[ArrayProperty(UploadedFile::class)]
        #[WithCast(ArrayToCollectionCast::class)]
        /** @var UploadedFile[] */
        public Collection $files,
    ) {}
}
