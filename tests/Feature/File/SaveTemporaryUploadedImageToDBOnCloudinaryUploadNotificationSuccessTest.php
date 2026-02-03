<?php

namespace Tests\Feature\File;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Http\Controllers\FileController;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        FileController::class
    )
]
class SaveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccessTest extends StoreTestCase
{
    use MediaServiceMocks;

    #[Test,
        Group('saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess'),
        Group('success'),
        Group('200'),
    ]
    public function cloudinary_notification_url_saves_temporary_uploaded_images_for_user_model_to_database_on_cloudinary_successfull_notificatoin_from_front_end_with_200_status(): void
    {

        $cloudinary_notificatoin_url_request_data =
            CloudinaryNotificationUrlRequestData::Create();

        MediaServiceMocks::mockTemporaryUploadImageToFolderFromCloudinaryNotification(
            $this->store->id
        );

        $response =
           $this
               ->withRouteName(
                   route(
                       'files.cloudinary-notifications-url'
                   )
               )
               ->postJsonData(
                   $cloudinary_notificatoin_url_request_data
                       ->toArray()
               );

        $response->assertStatus(200);

    }
}
