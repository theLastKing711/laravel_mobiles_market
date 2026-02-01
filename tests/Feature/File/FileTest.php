<?php

namespace Tests\Feature\File;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
use App\Models\Media;
use App\Models\TemporaryUploadedImages;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\File\Abstractions\FileTestCase;
use Tests\TraitMocks\CloudUploadServiceMocks;
use Tests\TraitMocks\MediaServiceMocks;

class FileTest extends FileTestCase
{
    use CloudUploadServiceMocks, MediaServiceMocks;

    #[
        Test,
        Group('getCloudinaryPresignedUrls'),
        Group('success')
    ]
    public function get_cloudinary_presigned_urls_success_with_200_response(): void
    {
        $urls_count = 2;

        $this
            ->mockSignRequestsWithUniqueSignRequestSignatures(
                FileUploadDirectory::TEST_FOLDER,
                $urls_count
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'files.cloudinary-presigned-urls',

                       [
                           'urls_count' => $urls_count,
                       ]
                   )
               )

               ->getJsonData();

        $response->assertStatus(200);

    }

    #[Test,
        Group('getCloudinaryPresignedUrls'),
        Group('error')
    ]
    public function get_cloudinary_presigned_urls_with_duplicate_sign_request_signature_thrown_errors_with_500(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsThrowsDuplicateSignedRequestSignature(
                FileUploadDirectory::TEST_FOLDER,
                $urls_count
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'files.cloudinary-presigned-urls',
                       [
                           'urls_count' => $urls_count,
                       ]
                   )
               )
               ->getJsonData();

        $response->assertStatus(500);

    }

    #[Test,
        Group('saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess'),
        Group('success')
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

    private function deleteMediaByPublicIdRequest(string $public_id)
    {
        return
           $this
               ->withRouteName(
                   route(
                       'files.media.{public_id}.delete',
                       [
                           'public_id' => $public_id,
                       ]
                   )
               )
               ->deleteJsonData();
    }

    #[Test,
        Group('deleteMediaByPublicId'),
        Group('success'),
    ]
    public function delete_media_file_by_public_id_success_with_200_repsonse(): void
    {

        $media =
            Media::factory()
                ->forUserWithId($this->store->id)
                ->createOne();

        $public_id =
            $media
                ->public_id;

        $this->deleteMediaByPublicIdSuccess($public_id);

        $response
            =
            $this
                ->deleteMediaByPublicIdRequest($public_id);

        $response->assertStatus(200);

    }

    #[Test,
        Group('deleteMediaByPublicId'),
        Group('error'),
    ]
    public function delete_non_existing_media_file_on_cloudinary_by_public_id_errors_with_500_repsonse(): void
    {

        $media =
            Media::factory()
                ->forUserWithId($this->store->id)
                ->createOne();

        $public_id =
            $media->public_id;

        $this
            ->deleteMediaByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response
            =
            $this
                ->deleteMediaByPublicIdRequest($public_id);

        $response->assertStatus(500);

    }

    private function deleteTemporaryUploadedImageByPublicIdRequest(string $public_id)
    {
        return
           $this
               ->withRouteName(
                   route(
                       'files.temporary-uploaded-images.{public_id}.delete',
                       [
                           'public_id' => $public_id,
                       ]
                   )
               )
               ->deleteJsonData();
    }

    #[
        Test,
        Group('deleteTemporaryUploadedImageByPublicId'),
        Group('success')
    ]
    public function delete_temporary_uploaded_file_by_public_id_success_with_200_repsonse(): void
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
               ->deleteTemporaryUploadedImageByPublicIdRequest($public_id);

        $response->assertStatus(200);

    }

    #[
        Test,
        Group('deleteTemporaryUploadedImageByPublicId'),
        Group('error')
    ]
    public function delete_non_existing_temporary_uploaded_file_on_cloudinary_by_public_id_errors_with_500_repsonse(): void
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

        $response
            =
            $this
                ->deleteTemporaryUploadedImageByPublicIdRequest(
                    $public_id
                );

        $response->assertStatus(500);

    }
}
