<?php

namespace Tests\TraitMocks;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Exceptions\Api\Cloudinary\FailedToDeleteImageException;
use App\Facades\MediaService;
use App\Models\TemporaryUploadedImages;

trait MediaServiceMocks
{
    use CloudUploadServiceMocks;

    public static function mockTemporaryUploadImageToFolderFromCloudinaryNotification(int $user_id)
    {
        MediaService::partialMock()
            ->expects(
                something: 'createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification'
            )
            ->withArgs(
                function ($arg) {
                    if ($arg instanceof CloudinaryNotificationUrlRequestData) {
                        return true;
                    }

                    return false;
                }
            )
            ->andReturn(
                TemporaryUploadedImages::factory()
                    ->forUserWithId($user_id)
                    ->create()
            );
    }

    public static function mockTemporaryUploadMobileOfferImageFromCloudinaryNotification()
    {

        MediaService::partialMock()
            ->expects(
                'temporaryUploadMobileOfferImageFromCloudinaryNotification'
            )
            ->withArgs(
                function ($arg) {
                    if ($arg instanceof CloudinaryNotificationUrlRequestData) {
                        return true;
                    }

                    return false;
                }
            );

    }

    public function mockDeleteFileByPublicIdSuccess(string $public_id)
    {

        MediaService::partialMock()
            ->expects(
                'deleteFileByPublicId'
            )
            ->with($public_id)
            ->andReturn(true);

    }

    public function mockDeleteFileByPublicIdThrowsFailedToDeleteImageException(string $public_id)
    {

        MediaService::partialMock()
            ->expects(
                'deleteFileByPublicId'
            )
            ->with($public_id)
            ->andThrow(FailedToDeleteImageException::class);
    }
}
