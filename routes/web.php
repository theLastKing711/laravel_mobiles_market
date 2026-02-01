<?php

use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::prefix('files')
    ->middleware(
        [
            'api',
            'auth:sanctum',
            RolesEnum::oneOfRolesMiddleware(RolesEnum::ADMIN, RolesEnum::STORE, RolesEnum::USER),
        ]
    )
    ->group(function () {

        Route::get('cloudinary-presigned-urls', [FileController::class, 'getTestCloudinaryPresignedUrls'])
            ->name(
                'files.cloudinary-presigned-urls'
            );

        Route::post('cloudinary-notifications-url', [FileController::class, 'saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess'])
            ->name(
                'files.cloudinary-notifications-url'
            );

        Route::delete('medias/{public_id}', [FileController::class, 'deleteMediaByPublicId'])
            ->name(
                'files.media.{public_id}'
            );

        Route::delete('tempoaray-uploaded-images/{public_id}', [FileController::class, 'deleteTemporaryUploadedImageByPublicId'])
            ->name(
                'files.temporary-uploaded-image.{public_id}'
            );
    });
