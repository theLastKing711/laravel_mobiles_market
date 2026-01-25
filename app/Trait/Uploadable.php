<?php

namespace App\Trait;

use App\Data\Shared\File\CloudinaryEagerUploadData;
use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
use App\Interfaces\IUploadable;
use App\Models\TemporaryUploadedImages;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use CloudinaryLabs\CloudinaryLaravel\Model\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

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
     * Summary of temporaryUploadedImages
     *
     * @return MorphMany<TemporaryUploadedImages, $this>|MorphMany<TemporaryUploadedImages, \Eloquent>
     */
    public function temporaryUploadedImages(): MorphMany
    {
        return $this->morphMany(TemporaryUploadedImages::class, 'uploadable');
    }

    /**
     * Summary of temporaryUploadedByCollectionName
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadedByCollectionName(FileUploadDirectory $file_upload_directory): Collection
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

    /**
     * Summary of temporaryUploadedByCollectionName
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadedMobileOffers(FileUploadDirectory $file_upload_directory): Collection
    {

        return
            $this
                ->temporaryUploadedImages()
                ->where(
                    'collection_name',
                    FileUploadDirectory::MOBILE_OFFERS
                )
                ->get();

    }

    /**
     * Summary of temporaryUploadedByCollectionName
     *
     * @return TemporaryUploadedImages
     */
    public function temporaryUploadMobileOfferImageFromCloudinaryNotification(CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData)
    {

        return
            $this
                ->temporaryUploadImageToFolderFromCloudinaryNotification(
                    $cloudinaryNotificationUrlRequestData,
                    FileUploadDirectory::MOBILE_OFFERS
                );

        // return $request;

        // $temporary_uploaded_image =
        //     TemporaryUploadedImages::fromCloudinaryEagerUploadedImage(
        //         $cloudinaryNotificationUrlRequestData,
        //         FileUploadDirectory::MOBILE_OFFERS
        //     );

        // return
        //    $this
        //        ->temporaryUploadedImages()
        //        ->create(
        //            $temporary_uploaded_image
        //                ->toArray()
        //        );

    }

    /**
     * Summary of temporaryUploadedByCollectionName
     *
     * @return TemporaryUploadedImages
     */
    private function temporaryUploadImageToFolderFromCloudinaryNotification(CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData, FileUploadDirectory $file_upload_directory)
    {
        // return $request;

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

    // /**
    //  * Summary of temporaryUploadedByCollectionName
    //  *
    //  * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
    //  */
    // public function temporaryUploadImagesFromCloudinaryNotification(CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData): Collection
    // {

    //     /** @var Collection<TemporaryUploadedImages> $temporary_images_to_upload */
    //     $temporary_images_to_upload =
    //             $cloudinaryNotificationUrlRequestData
    //                 ->eager
    //                 ->map(
    //                     function (CloudinaryEagerUploadData $eager_item) use ($cloudinaryNotificationUrlRequestData) {
    //                         $temporary_uploaded_image = new TemporaryUploadedImages;
    //                         // $temporary_uploaded_image->user_id = FacadesAuth::User()->id;
    //                         $temporary_uploaded_image->public_id = $cloudinaryNotificationUrlRequestData->public_id;
    //                         $temporary_uploaded_image->file_name = Str::random(6);
    //                         $temporary_uploaded_image->file_url = $eager_item->secure_url;
    //                         $temporary_uploaded_image->size = $eager_item->bytes;
    //                         $temporary_uploaded_image->file_type = $cloudinaryNotificationUrlRequestData->type;
    //                         $temporary_uploaded_image->collection_name = FileUploadDirectory::MOBILE_OFFERS;
    //                         $temporary_uploaded_image->thumbnail_url = $eager_item->secure_url;
    //                     }
    //                 );

    //     return
    //         $this
    //             ->temporaryUploadedImages()
    //             ->createMany(
    //                 $temporary_images_to_upload
    //                     ->toArray()
    //             );

    // }

}
