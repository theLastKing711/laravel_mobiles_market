<?php

namespace App\Data\Shared\File;

use App\Enum\CloudinaryTransformationEnum;
use Illuminate\Support\Collection;
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

    /**
     * @return Collection<CloudinaryEagerUploadData>
     **/
    public static function createRandomImage()
    {
        return collect([
            static::createRandomMainImage(),
            static::createRandomThumbImage(),
        ]);
    }

    public static function createRandomMainImage()
    {
        return new self(
            CloudinaryTransformationEnum::MAIN,
            fake()->numberBetween(400, 600),
            fake()->numberBetween(400, 600),
            fake()->numberBetween(3000, 4000),
            'webp',
            fake()->url(),
            fake()->url(),
        );
    }

    public static function createRandomThumbImage()
    {
        return new self(
            CloudinaryTransformationEnum::THUMBNAIL,
            fake()->numberBetween(200, 300),
            fake()->numberBetween(200, 300),
            fake()->numberBetween(1000, 2000),
            'webp',
            fake()->url(),
            fake()->url(),
        );
    }
}
