<?php

use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\User\Auth\ChangePasswordController;
use App\Http\Controllers\User\Auth\ChangePhoneNumberController;
use App\Http\Controllers\User\Auth\DeleteAccountController;
use App\Http\Controllers\User\Auth\SignOutController;
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
use App\Http\Controllers\User\Reporting\BlockUserController;
use App\Http\Middleware\OptionalAuthSanctum;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->middleware(['api'])
    ->group(function () {
        $userRole = RolesEnum::USER->value;


        //does not require to be logged in, only sanctum token
        Route::prefix('mobile-offers')
            ->middleware(
                [
                    OptionalAuthSanctum::class, // if token is passed, and user valid using token, sign him in, to access him in controller using Auth::user(), if not passed or failed to sign in, guest can view the page and Auth::User returns null
                ]
            )
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


        // must be logged in after making request to /sanctum and obtaining token to send here
        Route::middleware(
            [
                'auth:sanctum',
                RolesEnum::oneOfRolesMiddleware(RolesEnum::USER, RolesEnum::ADMIN, RolesEnum::STORE),
            ]
        )
            // auth:sanctum check if user is logged in (middleware('auth')),
            ->group(function () {

                Route::prefix('reportings')->group(function () {

                    Route::prefix('block-user')->group(function () {

                        Route::patch('{id}', BlockUserController::class);

                    });

                });

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

                    Route::delete('', DeleteAccountController::class);

                    Route::patch('sign-out', SignOutController::class);


                });

                route::prefix('my-mobile-offers')
                    ->group(function () {
                        Route::get('', GetMyMobileOffersController::class)
                            ->name('users.my-mobile-offers');

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
                            ->name('users.my-mobile-offers.post');

                        Route::patch('/{id}/sold', SellMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.sold');

                        Route::patch('{id}', UpdateMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.patch');

                        Route::delete('{id}', DeleteMobileOfferController::class)
                            ->name('users.my-mobile-offers.{id}.delete');

                    });


                Route::prefix('mobile-offer-features')
                    ->group(function () {
                        Route::get('list', GetMobileOfferFeaturesListController::class);
                    });


            });

        Route::prefix('auth')->group(function () {

            Route::prefix('login')->group(function () {

                Route::post('phone-number-step', AddPhoneNumberLoginStepController::class)
                    ->name(
                        'users.auth.login.phone-number-step'
                    );

                Route::post('login', LoginController::class)
                    ->name(
                        'users.auth.login.login'
                    );

            });

            Route::prefix('registeration')->group(function () {

                Route::post('phone-number-step', AddPhoneNumberRegisterationStepController::class)
                    ->name(
                        'users.auth.registeration.phone-number-step'
                    );

                Route::post('register', RegisterController::class)
                    ->name(
                        'users.auth.registeration.register'
                    );

            });

        });

    });
