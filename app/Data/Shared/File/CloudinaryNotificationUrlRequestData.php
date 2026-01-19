<?php

namespace App\Data\Shared\File;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Enum\CloudinaryTransformationEnum;
use App\Models\Media;
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

    public static function fromMedia(Media $media): self
    {

        $eager_images =
           collect(
               [
                   new CloudinaryEagerUploadData(
                       CloudinaryTransformationEnum::MAIN,
                       fake()->numberBetween(400, 600),
                       fake()->numberBetween(400, 600),
                       fake()->numberBetween(3000, 4000),
                       'webp',
                       fake()->url(),
                       fake()->url(),
                   ),
                   new CloudinaryEagerUploadData(
                       CloudinaryTransformationEnum::THUMBNAIL,
                       fake()->numberBetween(200, 300),
                       fake()->numberBetween(200, 300),
                       fake()->numberBetween(1000, 2000),
                       'webp',
                       fake()->url(),
                       fake()->url(),
                   ),
               ]
           );

        return new self(
            $media->public_id,
            $media->file_type,
            $media->size,
            $eager_images
        );
    }
}
