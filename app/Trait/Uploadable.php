<?php

namespace App\Trait;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
use App\Interfaces\IUploadable;
use App\Models\TemporaryUploadedImages;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use CloudinaryLabs\CloudinaryLaravel\Model\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * MediaAlly
 *
 * Provides functionality for attaching Cloudinary files to an eloquent model.
 * Whether the model should automatically reload its media relationship after modification.
 *
 * @phpstan-require-implements IUploadable
 *
 * @phpstan-require-extends \Illuminate\Database\Eloquent\Model
 */
trait Uploadable
{
    use MediaAlly;

    /**
     * @return MorphMany<TemporaryUploadedImages, $this>|MorphMany<TemporaryUploadedImages, \Eloquent>
     */
    private function temporaryUploadedImages(): MorphMany
    {
        return $this->morphMany(TemporaryUploadedImages::class, 'uploadable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadedImagesByFolder(FileUploadDirectory $file_upload_directory): Collection
    {

        return
            $this
                ->temporaryUploadedImages()
                ->where(
                    'collection_name',
                    $file_upload_directory
                )
                ->get();

    }

    public function temporaryUploadImageToFolderFromCloudinaryNotification(
        CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData,
        FileUploadDirectory $file_upload_directory
    ): TemporaryUploadedImages {
        $temporary_uploaded_image =
            TemporaryUploadedImages::fromCloudinaryEagerUploadedImage(
                $cloudinaryNotificationUrlRequestData,
                $file_upload_directory
            );

        return
           $this
               ->temporaryUploadedImages()
               ->create(
                   $temporary_uploaded_image
                       ->toArray()
               );

    }
}
