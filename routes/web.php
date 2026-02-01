<?php

use App\Constants\Routes\FileRoute;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::prefix(FileRoute::MAIN_ROUTE)
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
                FileRoute::ROUTES['files.cloudinary-presigned-urls']['name']
            );

        Route::post('cloudinary-notifications-url', [FileController::class, 'saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess'])
            ->name(
                FileRoute::ROUTES['files.cloudinary-notifications-url']['name']
            );

        Route::delete('media/{public_id}', [FileController::class, 'deleteMediaByPublicId'])
            ->name(
                FileRoute::ROUTES['files.media.{public_id}.delete']['name']
            );

        Route::delete('temporary-uploaded-images/{public_id}', [FileController::class, 'deleteTemporaryUploadedImageByPublicId'])
            ->name(
                FileRoute::ROUTES['files.temporary-uploaded-images.{public_id}.delete']['name']
            );
    });
