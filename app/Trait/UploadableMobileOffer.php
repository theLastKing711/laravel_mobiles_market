<?php

namespace App\Trait;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
use App\Models\TemporaryUploadedImages;
use Illuminate\Database\Eloquent\Collection;

/**
 * MediaAlly
 *
 * Provides functionality for attaching Cloudinary files to an eloquent model.
 * Whether the model should automatically reload its media relationship after modification.
 *
 *
 * @phpstan-require-extends \Illuminate\Database\Eloquent\Model
 */
trait UploadableMobileOffer
{
    use Uploadable {
        temporaryUploadedImagesByFolder as private;
        temporaryUploadImageToFolderFromCloudinaryNotification as private;
    }

    // use Uploadable;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadedMobileOffers(FileUploadDirectory $file_upload_directory): Collection
    {

        return
            $this
                ->temporaryUploadedImagesByFolder(
                    $file_upload_directory
                );

    }

    /**
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

    }
}
