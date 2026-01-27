<?php

namespace Tests\Feature\User\MyMobileOffer\File;

use App\Models\Media;
use App\Models\MobileOffer;
use App\Models\TemporaryUploadedImages;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;
use Tests\TraitMocks\MediaServiceMocks;
use Tests\Traits\StoreTrait;

class MyMobileOfferFileTest extends UserTestCase
{
    use MediaServiceMocks, StoreTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->initializeStore();

        $this
            ->withRoutePaths(
                'my-mobile-offers',
                'files'
            );

        $this
            ->seed(
                [
                    MobileOfferFeatureSeeder::class,
                ]
            );

    }

    #[Test, Group('delete')]
    public function delete_for_temporary_uploaded_file_success()
    {

        $temporary_uploaded_image =
           TemporaryUploadedImages::factory()
               ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->mockDeleteFileByPublicIdSuccess(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(200);

    }

    #[Test, Group('delete')]
    public function delete_for_temporary_uploaded_file_errors_with_500_when_on_cloudinay_delete_file_fail()
    {

        $temporary_uploaded_image =
           TemporaryUploadedImages::factory()
               ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->mockDeleteFileByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(500);

    }

    #[Test, Group('delete')]
    public function delete_for_media_success()
    {

        $media =
           Media::factory()
               ->forUserWithId($this->store->id)
               ->create();

        $public_id =
            $media
                ->public_id;

        $this
            ->mockDeleteFileByPublicIdSuccess(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(200);

    }

    #[Test, Group('delete')]
    public function delete_for_media_errors_with_500_when_on_cloudinay_delete_file_fail()
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
            ->mockDeleteFileByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(500);

    }
}
