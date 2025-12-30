<?php

namespace App\Data\Shared\Casts;

use App\Data\Shared\Media\MediaData;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class MediallyToSingleMediaCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        $media = $value->first();

        if (! isset($media)) {
            return null;
        }

        $single_media_data = new MediaData(id: $media->id, file_url: $media->file_url);

        return $single_media_data;

    }
}
