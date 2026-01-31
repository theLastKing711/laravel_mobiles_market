<?php

namespace Tests\TraitMocks;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Exceptions\Api\Cloudinary\FailedToDeleteImageException;
use App\Facades\MediaService;
use App\Models\TemporaryUploadedImages;
use PHPUnit\Framework\Attributes\Group;

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

    #[
        Group(
            'deleteMediaByPublicId',
            'success'
        )
    ]
    public function deleteMediaByPublicIdSuccess(string $public_id)
    {

        MediaService::partialMock()
            ->expects(
                'deleteMediaByPublicId'
            )
            ->with($public_id)
            ->andReturn(true);

    }

    #[
        Group(
            'deleteMediaByPublicId',
            'error'
        )
    ]
    public function deleteMediaByPublicIdThrowsFailedToDeleteImageException(string $public_id)
    {

        MediaService::partialMock()
            ->expects(
                'deleteMediaByPublicId'
            )
            ->with($public_id)
            ->andThrow(FailedToDeleteImageException::class);
    }

    #[
        Group(
            'deleteTemporaryUploadedImageByPublicId',
            'success'
        )
    ]
    public function deleteTemporaryUploadedImageByPublicIdSuccess(string $public_id)
    {

        MediaService::partialMock()
            ->expects(
                'deleteTemporaryUploadedImageByPublicId'
            )
            ->with($public_id)
            ->andReturn(true);

    }

    #[
        Group(
            'deleteTemporaryUploadedImageByPublicId',
            'error'
        )
    ]
    public function deleteTemporaryUploadedImageByPublicIdThrowsFailedToDeleteImageException(string $public_id)
    {

        MediaService::partialMock()
            ->expects(
                'deleteTemporaryUploadedImageByPublicId'
            )
            ->with($public_id)
            ->andThrow(FailedToDeleteImageException::class);
    }
}
