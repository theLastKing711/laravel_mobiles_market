<?php

use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\User\Auth\ChangePasswordController;
use App\Http\Controllers\User\Auth\ChangePhoneNumberController;
use App\Http\Controllers\User\Auth\GetUserPhoneNumberController;
use App\Http\Controllers\User\Auth\Login\AddPhoneNumberLoginStepController;
use App\Http\Controllers\User\Auth\Login\LoginController;
use App\Http\Controllers\User\Auth\Registeration\AddPhoneNumberRegisterationStepController;
use App\Http\Controllers\User\Auth\Registeration\RegisterController;
use App\Http\Controllers\User\MobileOffer\CreateMobileOfferController;
use App\Http\Controllers\User\MobileOffer\DeleteMobileOfferController;
use App\Http\Controllers\User\MobileOffer\FavouriteMobileOfferController;
use App\Http\Controllers\User\MobileOffer\File\MyMobileOfferFileController;
use App\Http\Controllers\User\MobileOffer\GetFavouriteMobileOffersController;
use App\Http\Controllers\User\MobileOffer\GetMobileOfferController;
use App\Http\Controllers\User\MobileOffer\GetMyMobileOfferController;
use App\Http\Controllers\User\MobileOffer\GetMyMobileOffersController;
use App\Http\Controllers\User\MobileOffer\SearchMobilesOffersController;
use App\Http\Controllers\User\MobileOffer\SellMobileOfferController;
use App\Http\Controllers\User\MobileOffer\UpdateMobileOfferController;
use App\Http\Controllers\User\MobileOfferFeature\GetMobileOfferFeaturesListController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->middleware(['api'])
    ->group(function () {
        $userRole = RolesEnum::USER->value;

        // must be logged in after making request to /sanctum and obtaining token to send here
        Route::middleware(
            [
                'auth:sanctum',
                RolesEnum::oneOfRolesMiddleware(RolesEnum::USER, RolesEnum::ADMIN, RolesEnum::STORE),
            ]
        )
            // auth:sanctum check if user is logged in (middleware('auth')),
            ->group(function () {

                Route::prefix('auth')->group(function () {

                    Route::get('get-user-phone-number', GetUserPhoneNumberController::class)
                        ->name(
                            'users.auth.get-user-phone-number'
                        );
                    Route::patch('change-password', ChangePasswordController::class)
                        ->name(
                            'users.auth.change-password'
                        );
                    Route::patch('change-phone-number', ChangePhoneNumberController::class)
                        ->name(
                            'users.auth.change-phone-number'
                        );

                });

                route::prefix('my-mobile-offers')
                    ->group(function () {
                        Route::get('', GetMyMobileOffersController::class);

                        Route::prefix('files')->group(function () {

                            Route::get('cloudinary-presigned-urls', [MyMobileOfferFileController::class, 'getCloudinaryPresignedUrls'])
                                ->name('users.my-mobile-offers.files.cloudinary-presigned-urls');

                            Route::post('cloudinary-notifications-url', [MyMobileOfferFileController::class, 'saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess'])
                                ->name('users.my-mobile-offers.files.cloudinary-notifications-url');

                            Route::delete('medias/{public_id}', [MyMobileOfferFileController::class, 'deleteMediaByPublicId'])
                                ->name('users.my-mobile-offers.files.media.{public_id}');

                            Route::delete('temporary-uploaded-images/{public_id}', [MyMobileOfferFileController::class, 'deleteTemporaryUploadedImageByPublicId'])
                                ->name('users.my-mobile-offers.files.temporary-uploaded-image.{public_id}');

                        });

                        Route::get('{id}', GetMyMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.get');

                        Route::post('', CreateMobileOfferController::class)
                            ->name('users.my-mobile-offers');

                        Route::patch('/{id}/sold', SellMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.sold');

                        Route::patch('{id}', UpdateMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.patch');

                        Route::delete('{id}', DeleteMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.delete');

                    });

                Route::prefix('mobile-offers')
                    ->group(function () {
                        Route::get('search', SearchMobilesOffersController::class)
                            ->name(
                                'users.mobile-offers.search'
                            );

                        Route::get('favourites', GetFavouriteMobileOffersController::class)
                            ->name(
                                'users.mobile-offers.favourites'
                            );

                        Route::get('{id}', GetMobileOfferController::class)
                            ->name(
                                'users.mobile-offers.{id}'
                            );

                        Route::patch('{id}/favourite', FavouriteMobileOfferController::class)
                            ->name(
                                'users.mobile-offers.{id}.favourite'
                            );

                    });

                Route::prefix('mobile-offer-features')
                    ->group(function () {
                        Route::get('list', GetMobileOfferFeaturesListController::class);
                    });

            });

        Route::prefix('auth')->group(function () {

            Route::prefix('login')->group(function () {

                Route::post('phone-number-step', AddPhoneNumberLoginStepController::class);
                Route::post('login', LoginController::class);

            });

            Route::prefix('registeration')->group(function () {

                Route::post('phone-number-step', AddPhoneNumberRegisterationStepController::class);
                Route::post('register', RegisterController::class);

            });

        });

    });
