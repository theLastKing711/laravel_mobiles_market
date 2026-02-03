<?php

namespace Tests\Feature\User\MyMobileOffer\File;

use App\Http\Controllers\User\MobileOffer\File\MyMobileOfferFileController;
use App\Models\Media;
use App\Models\MobileOffer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        MyMobileOfferFileController::class
    )
]
class DeleteMediaByPublicIdTest extends StoreTestCase
{
    use MediaServiceMocks;

    private function deleteMediaByPublicIdRequest(string $public_id)
    {
        return
           $this
               ->withRouteName(
                   route(
                       'users.my-mobile-offers.files.media.{public_id}',
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
        Group('deleteMediaByPublicId'),
        Group('success'),
        Group('200')
    ]
    public function delete_media_success_with_200()
    {

        $media =
           Media::factory()
               ->forUserWithId($this->store->id)
               ->create();

        $public_id =
            $media
                ->public_id;

        $this
            ->deleteMediaByPublicIdSuccess(
                $public_id
            );

        $response =
            $this
                ->deleteMediaByPublicIdRequest(
                    $public_id
                );

        $response->assertStatus(200);

    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group(MyMobileOfferFileController::class),
        Group('deleteMediaByPublicId'),
        Group('error'),
        Group('500')
    ]
    public function delete_media_errors_with_500_when_on_cloudinay_delete_file_fail_errors_with_500()
    {

        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->createOne();

        $temporary_uploaded_image =
           Media::factory()
               ->forMobileOfferWithId($mobile_offer->id)
               ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->deleteMediaByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response =
            $this
                ->deleteMediaByPublicIdRequest(
                    $public_id
                );

        $response->assertStatus(500);

    }
}
