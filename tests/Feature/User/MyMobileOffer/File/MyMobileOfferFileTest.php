<?php

namespace Tests\Feature\User\MyMobileOffer\File;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
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

    #[
        Test,
        Group('getCloudinaryPresignedUrls')
    ]
    public function get_cloudinary_presigned_urls_success_with_200_response(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsWithUniqueSignRequestSignatures(
                FileUploadDirectory::MOBILE_OFFERS,
                $urls_count
            );

        $this
            ->withRoutePaths(
                'cloudinary-presigned-urls'
            );

        $response =
           $this
               ->withQueryParameters([
                   'urls_count' => $urls_count,
               ])
               ->getJsonData();

        $response->assertStatus(200);

    }

    #[
        Test, Group('getCloudinaryPresignedUrls')
    ]
    public function get_cloudinary_presigned_urls_with_duplicate_sign_request_signature_thrown_errors_with_500(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsThrowsDuplicateSignedRequestSignature(
                FileUploadDirectory::MOBILE_OFFERS,
                $urls_count
            );

        $this
            ->withRoutePaths(
                'cloudinary-presigned-urls'
            );

        $response =
           $this
               ->withQueryParameters([
                   'urls_count' => $urls_count,
               ])
               ->getJsonData();

        $response->assertStatus(500);

    }

    #[
        Test,
        Group('updateMediaOnCloudinaryUploadNotificationSuccess')
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
               ->withRoutePaths(
                   'cloudinary-notifications-url'
               )
               ->postJsonData(
                   $cloudinary_notificatoin_url_request_data
                       ->toArray()
               );

        $response->assertStatus(201);

    }

    #[
        Test,
        Group('delete')
    ]
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

    #[
        Test,
        Group('delete')
    ]
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

    #[
        Test,
        Group('delete')
    ]
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

    #[
        Test,
        Group('delete')
    ]
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
