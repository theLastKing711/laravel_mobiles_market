<?php

namespace Tests\Feature\Shared;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\CloudinaryTransformationEnum;
use App\Enum\FileUploadDirectory;
use App\Exceptions\Api\Cloudinary\FailedToDeleteImageException;
use App\Facades\MediaService;
use App\Models\Media;
use App\Models\TemporaryUploadedImages;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\TraitMocks\CloudUploadServiceMocks;
use Tests\Traits\AdminTrait;

class MediaServiceTest extends TestCase
{
    use AdminTrait, CloudUploadServiceMocks, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->seed(
                [
                    RolesAndPermissionsSeeder::class,
                ]
            );

        $this
            ->initializeAdmin();

    }

    #[
        Test,
        group('createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification')
    ]
    public function media_service_creates_temporary_uploaded_image_from_cloudinary_upload_notification_success(): void
    {

        // arrange
        $cloudinary_notificatoin_url_request_data =
           CloudinaryNotificationUrlRequestData::Create();

        // act
        $temporary_uploaded_image =
            MediaService::createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification(
                $cloudinary_notificatoin_url_request_data,
                FileUploadDirectory::TEST_FOLDER
            );

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

        // assert
        $this
            ->assertDatabaseCount(
                TemporaryUploadedImages::class,
                1
            );

        $this
            ->assertDatabaseHas(
                TemporaryUploadedImages::class,
                [
                    'public_id' => $cloudinary_notificatoin_url_request_data->public_id,
                    'file_url' => $mainImage->secure_url,
                    'thumbnail_url' => $thumbImage->secure_url,
                    'collection_name' => FileUploadDirectory::TEST_FOLDER,
                ]
            );

        $this
            ->assertEquals(
                $cloudinary_notificatoin_url_request_data->public_id,
                $temporary_uploaded_image->public_id,
            );

        $this
            ->assertEquals(
                FileUploadDirectory::TEST_FOLDER,
                $temporary_uploaded_image->collection_name,
            );

    }

    #[
        Test,
        Group('deleteFileByPublicId')
    ]
    public function media_service_delete_media_by_public_id_for_temporary_uploaded_file_success(): void
    {

        // arrange
        $temporary_uploaded_image = TemporaryUploadedImages::factory()
            ->createOne();

        $this
            ->mockDestroySuccess(
                public_id: $temporary_uploaded_image
                    ->public_id
            );

        // act
        MediaService::deleteFileByPublicId(
            $temporary_uploaded_image
                ->public_id
        );

        $this
            ->assertDatabaseCount(
                TemporaryUploadedImages::class,
                0
            );

    }

    #[
        Test,
        Group('deleteFileByPublicId')
    ]
    public function media_service_delete_file_by_public_id_for_temporary_uploaded_file_doesnt_delete_file_from_database_on_cloudinary_delete_fail(): void
    {
        try {
            // arrange
            $temporary_uploaded_image = TemporaryUploadedImages::factory()
                ->createOne();

            $this
                ->mockDestroyFailure(
                    public_id: $temporary_uploaded_image
                        ->public_id
                );

            // act
            MediaService::deleteFileByPublicId(
                $temporary_uploaded_image
                    ->public_id
            );

        } catch (FailedToDeleteImageException $e) {
            $this
                ->assertDatabaseCount(
                    TemporaryUploadedImages::class,
                    1
                );

        }

    }

    #[
        Test,
        Group('deleteFileByPublicId')
    ]
    public function media_service_delete_media_by_public_id_for_temporary_uploaded_file_throws_failed_to_delete_image_exception_on_cloudinary_delete_error(): void
    {

        // arrange
        $temporary_uploaded_image = TemporaryUploadedImages::factory()
            ->createOne();

        $this
            ->mockDestroyFailure(
                public_id: $temporary_uploaded_image
                    ->public_id
            );

        // with this asserts dont work after act, we can use try catch to solve it
        // but should be in another because they dont work together.
        $this
            ->expectException(
                FailedToDeleteImageException::class
            );

        // act
        MediaService::deleteFileByPublicId(
            $temporary_uploaded_image
                ->public_id
        );

    }

    #[
        Test,
        Group('deleteFileByPublicId')
    ]
    public function media_service_deletes_media_by_public_id_success(): void
    {

        $this
            ->initializeAdmin();

        // arrange
        $media = Media::factory()
            ->forUserWithId($this->admin->id)
            ->createOne();

        $this
            ->mockDestroySuccess(
                $media
                    ->public_id
            );

        // act
        MediaService::deleteFileByPublicId(
            $media
                ->public_id
        );

        $this
            ->assertDatabaseCount(
                Media::class,
                0
            );

    }

    #[
        Test,
        Group('deleteFileByPublicId')
    ]
    public function media_service_delete_media_by_public_id_throws_failed_to_delete_image_exception_on_cloudinary_delete_error(): void
    {

        // arrange
        $media = Media::factory()
            ->forUserWithId($this->admin->id)
            ->createOne();

        $this
            ->mockDestroyFailure(
                $media
                    ->public_id
            );

        $this
            ->expectException(
                FailedToDeleteImageException::class
            );

        // act
        MediaService::deleteFileByPublicId(
            $media
                ->public_id
        );

    }

    #[
        Test,
        Group('deleteFileByPublicId')
    ]
    public function media_service_delete_file_by_public_id_doesnt_delete_media_from_database_on_cloudinary_delete_fail(): void
    {
        try {
            // arrange
            $media = Media::factory()
                ->forUserWithId($this->admin->id)
                ->createOne();

            $this
                ->mockDestroyFailure(
                    $media
                        ->public_id
                );

            // act
            MediaService::deleteFileByPublicId(
                $media
                    ->public_id
            );

        } catch (FailedToDeleteImageException $e) {
            $this
                ->assertDatabaseCount(
                    Media::class,
                    1
                );

        }

    }
}
