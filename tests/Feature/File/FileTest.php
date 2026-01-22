<?php

namespace Tests\Feature\File;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\CloudinaryTransformationEnum;
use App\Enum\FileUploadDirectory;
use App\Models\Media;
use App\Models\MobileOffer;
use App\Models\TemporaryUploadedImages;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\File\Abstractions\FileTestCase;
use Tests\TraitMocks\CloudUploadServiceMocks;

class FileTest extends FileTestCase
{
    use CloudUploadServiceMocks;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function get_cloudinary_presigned_url_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'cloudinary-presigned-url'
            );

        $response =
           $this
               ->getJsonData();

        $response->assertStatus(200);

    }

    #[Test]
    public function get_cloudinary_presigned_urls_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'cloudinary-presigned-urls'
            );

        $response =
           $this
               ->withQueryParameters([
                   'urls_count' => 2,
               ])
               ->getJsonData();

        $response->assertStatus(200);

    }

    #[Test]
    public function get_cloudinary_presigned_urls_with_duplicate_signatures_from_server_code_errors_with_500_response(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsWithStaticReturn(
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

    #[Test]
    public function delete_temporary_uploaded_file_by_public_id_success_with_200_repsonse(): void
    {

        $temporary_uploaded_image =
            TemporaryUploadedImages::factory()
                ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->mockDestroySuccess(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(200);

        $this
            ->assertDatabaseMissing(
                TemporaryUploadedImages::class,
                [
                    'id' => $temporary_uploaded_image->id,
                    // 'public_id' => $temporary_uploaded_image->public_id,
                ]
            );

    }

    #[Test]
    public function delete_non_existing_temporary_uploaded_file_on_cloudinary_by_public_id_errors_with_500_repsonse(): void
    {

        $temporary_uploaded_image =
            TemporaryUploadedImages::factory()
                ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        // retunr "not_found' which stimulate provided public id is not on cloudinary
        $this
            ->mockDestroyFailure(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(500);

        $this
            ->assertDatabaseHas(
                TemporaryUploadedImages::class,
                [
                    'id' => $temporary_uploaded_image->id,
                ]
            );

    }

    #[Test]
    public function delete_media_file_by_public_id_success_with_200_repsonse(): void
    {

        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->has(
                    Media::factory(),
                    'mainImage'
                )
                ->createOne();

        $media =
            $mobile_offer
                ->mainImage;

        $public_id =
            $media
                ->public_id;

        $this
            ->mockDestroySuccess(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(200);

        $this
            ->assertDatabaseMissing(
                Media::class,
                [
                    'id' => $media->id,
                ]
            );

    }

    #[Test]
    public function delete_non_existing_media_file_on_cloudinary_by_public_id_errors_with_500_repsonse(): void
    {

        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->has(
                    Media::factory(),
                    'mainImage'
                )
                ->createOne();

        $media =
            $mobile_offer
                ->mainImage;

        $public_id =
            $media
                ->public_id;

        // retunr "not_found' which stimulate provided public id is not on cloudinary
        $this
            ->mockDestroyFailure(
                $public_id
            );

        $response =
           $this
               ->withRoutePaths(
                   $public_id
               )
               ->deleteJsonData();

        $response->assertStatus(500);

        $this
            ->assertDatabaseHas(
                Media::class,
                [
                    'id' => $media->id,
                ]
            );

    }

    #[Test]
    public function update_temporary_uploaded_images_for_model_on_cloudinary_successfull_notificatoin_from_front_end_with_201_status(): void
    {

        $media =
            Media::factory()
                ->for(
                    MobileOffer::factory()
                        ->forUserWithId($this->store->id),
                    'medially'

                )
                ->createOne();

        $cloudinary_notificatoin_url_request_data =
           CloudinaryNotificationUrlRequestData::fromMedia(
               $media
           );

        $response =
           $this
               ->withRoutePaths(
                   'mobile-offer-cloudinary-notifications-url'
               )
               ->postJsonData(
                   $cloudinary_notificatoin_url_request_data
                       ->toArray()
               );

        $response->assertStatus(201);

        $mainImage =
        $cloudinary_notificatoin_url_request_data
            ->eager
            ->firstWhere(
                'transformation',
                CloudinaryTransformationEnum::MAIN
            );

        $thumbImage =
            $cloudinary_notificatoin_url_request_data
                ->eager
                ->firstWhere(
                    'transformation',
                    CloudinaryTransformationEnum::THUMBNAIL
                );

        $this
            ->assertDatabaseHas(
                TemporaryUploadedImages::class,
                [
                    'public_id' => $cloudinary_notificatoin_url_request_data->public_id,
                    'file_url' => $mainImage->secure_url,
                    'thumbnail_url' => $thumbImage->secure_url,
                ]
            );

    }
}
