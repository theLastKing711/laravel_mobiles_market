<?php

namespace App\Services;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
use App\Exceptions\Api\Cloudinary\FailedToDeleteImageException;
use App\Facades\CloudUploadService;
use App\Models\Media;
use App\Models\TemporaryUploadedImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class mediaService
{
    public function createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification(
        CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData,
        FileUploadDirectory $file_upload_directory
    ): TemporaryUploadedImages {

        $unsaved_temporary_uploaded_image =
            TemporaryUploadedImages::fromCloudinaryEagerUploadedImage(
                $cloudinaryNotificationUrlRequestData,
                $file_upload_directory
            );

        return
           Auth::User()
               ->temporaryUploadedImages()
               ->create(
                   $unsaved_temporary_uploaded_image
                       ->toArray()
               );

    }

    /**
     * @return TemporaryUploadedImages
     *
     * @throws FailedToDeleteImageException
     */
    public function deleteFileByPublicId(string $public_id)
    {

        $public_id =
             str_replace(
                 '-',
                 '/',
                 $public_id
             );

        DB::beginTransaction();

        // if image is created before parent model is created (i.e on create page)
        TemporaryUploadedImages::query()
            ->firstWhere(
                'public_id',
                $public_id
            )
            ?->delete();

        // // if image is created after parent model is created(i.e on update page)
        Media::query()
            ->firstWhere(
                'public_id',
                $public_id
            )
            ?->delete();

        $media_has_been_deleted =
            CloudUploadService::destroy(
                $public_id
            );

        if (! $media_has_been_deleted) {

            DB::rollBack();
            throw new FailedToDeleteImageException;
        }

        DB::commit();

    }

    // mobile-offer specific methods
    /**
     * @return TemporaryUploadedImages
     */
    public function temporaryUploadMobileOfferImageFromCloudinaryNotification(CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData)
    {

        return
            $this
                ->createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification(
                    $cloudinaryNotificationUrlRequestData,
                    FileUploadDirectory::MOBILE_OFFERS
                );

    }
}
