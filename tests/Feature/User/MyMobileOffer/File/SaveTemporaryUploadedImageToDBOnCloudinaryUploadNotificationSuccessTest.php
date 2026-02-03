<?php

namespace Tests\Feature\User\MyMobileOffer\File;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Http\Controllers\User\MobileOffer\File\MyMobileOfferFileController;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        MyMobileOfferFileController::class
    )
]
class SaveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccessTest extends StoreTestCase
{
    use MediaServiceMocks;

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group(MyMobileOfferFileController::class),
        Group('SaveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccessTest'),
        Group('success'),
        Group('200')
    ]
    public function save_temporary_uploaded_images_for_mobile_model_to_database_on_cloudinary_upload_success_notificatoin_from_front_end_with_200_status(): void
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
                       'users.my-mobile-offers.files.cloudinary-notifications-url',
                   )
               )
               ->postJsonData(
                   $cloudinary_notificatoin_url_request_data
                       ->toArray()
               );

        $response->assertStatus(201);

    }
}
