<?php

namespace Tests\TraitMocks;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Exceptions\Api\Cloudinary\FailedToDeleteImageException;
use App\Facades\MediaService;

trait MediaServiceMocks
{
    use CloudUploadServiceMocks;

    public static function mockTemporaryUploadImageToFolderFromCloudinaryNotification()
    {
        MediaService::partialMock()
            ->expects(
                something: 'temporaryUploadImageToFolderFromCloudinaryNotification'
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
            ->andReturn([
                'result' => 'ok',
            ]);

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
