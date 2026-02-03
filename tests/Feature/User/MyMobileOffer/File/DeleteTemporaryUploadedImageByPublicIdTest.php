<?php

namespace Tests\Feature\User\MyMobileOffer\File;

use App\Http\Controllers\User\MobileOffer\File\MyMobileOfferFileController;
use App\Models\TemporaryUploadedImages;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        MyMobileOfferFileController::class
    )
]
class DeleteTemporaryUploadedImageByPublicIdTest extends StoreTestCase
{
    use MediaServiceMocks;

    private function deleteTemporaryUploadedImageByPublicIdRequest(string $public_id)
    {
        return
           $this
               ->withRouteName(
                   route(
                       'users.my-mobile-offers.files.temporary-uploaded-image.{public_id}',
                       [
                           'public_id' => $public_id,
                       ]
                   )
               )
               ->deleteJsonData();
    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group(MyMobileOfferFileController::class),
        Group('deleteTemporaryUploadedImageByPublicId'),
        Group('success'),
        Group('200')
    ]
    public function delete_temporary_uploaded_file_success()
    {

        $temporary_uploaded_image =
           TemporaryUploadedImages::factory()
               ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->deleteTemporaryUploadedImageByPublicIdSuccess(
                $public_id
            );

        $response =
            $this
                ->deleteTemporaryUploadedImageByPublicIdRequest(
                    $public_id
                );

        $response->assertStatus(200);

    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group(MyMobileOfferFileController::class),
        Group('deleteTemporaryUploadedImageByPublicId'),
        Group('error'),
        Group('500')
    ]
    public function delete_temporary_uploaded_file_errors_with_500_when_on_cloudinay_delete_file_fail()
    {

        $temporary_uploaded_image =
           TemporaryUploadedImages::factory()
               ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->deleteTemporaryUploadedImageByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response =
            $this
                ->deleteTemporaryUploadedImageByPublicIdRequest(
                    $public_id
                );

        $response->assertStatus(500);

    }
}
