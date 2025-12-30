<?php

namespace App\Models;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\CloudinaryTransformationEnum;
use App\Enum\FileUploadDirectory;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TemporaryUploadedImages extends Model
{
    /** @use HasFactory<\Database\Factories\TemporaryUploadedImagesFactory> */
    use HasFactory;

    /**
     * Get the user that owns the TemporaryUploadedImages
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function fromCloudinaryUploadResponse(CloudinaryEngine $response_file, int $user_id): self
    {

        $response =
            $response_file
                ->getResponse();

        // first transformed file
        $first_eager_response =
            $response['eager'][0];

        // $media->file_url = $response_file->getSecurePath();
        // $media->size = $first_eager_response->getSize();

        $temporary_uploaded_image = new TemporaryUploadedImages;
        $temporary_uploaded_image->user_id = $user_id;
        $temporary_uploaded_image->public_id = $response_file->getPublicId();
        $temporary_uploaded_image->file_name = $response_file->getFileName();
        $temporary_uploaded_image->file_url = $first_eager_response['secure_url'];
        $temporary_uploaded_image->size = $first_eager_response['bytes'];
        $temporary_uploaded_image->file_type = $response_file->getFileType();

        return $temporary_uploaded_image;
    }

    public static function fromCloudinaryEagerUploadedImage(CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData, FileUploadDirectory $collection_name): self
    {

        $temporary_uploaded_image = new TemporaryUploadedImages;

        // /** @var Collection<TemporaryUploadedImages> $temporary_images_to_upload */
        // $temporary_images_to_upload =
        $cloudinaryNotificationUrlRequestData
            ->eager
            ->each(
                function ($eager_item) use ($cloudinaryNotificationUrlRequestData, $collection_name, $temporary_uploaded_image) {

                    if ($eager_item->transformation == CloudinaryTransformationEnum::MAIN) {

                        $temporary_uploaded_image->public_id = $cloudinaryNotificationUrlRequestData->public_id;
                        $temporary_uploaded_image->file_name = Str::random(6);
                        $temporary_uploaded_image->file_url = $eager_item->secure_url;
                        $temporary_uploaded_image->size = $eager_item->bytes;
                        $temporary_uploaded_image->file_type = $cloudinaryNotificationUrlRequestData->type;
                        $temporary_uploaded_image->collection_name = $collection_name;
                    } else {
                        $temporary_uploaded_image->thumbnail_url = $eager_item->secure_url;
                    }

                    // return $temporary_uploaded_image;
                }
            );

        return $temporary_uploaded_image;

    }
}
