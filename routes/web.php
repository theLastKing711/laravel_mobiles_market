<?php

use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\FileController;
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
use App\Http\Controllers\User\MobileOffer\GetFavouriteMobileOffersController;
use App\Http\Controllers\User\MobileOffer\GetMobileOfferController;
use App\Http\Controllers\User\MobileOffer\GetMyMobileOfferController;
use App\Http\Controllers\User\MobileOffer\GetMyMobileOffersController;
use App\Http\Controllers\User\MobileOffer\SearchMobilesOffersController;
use App\Http\Controllers\User\MobileOffer\SellMobileOfferController;
use App\Http\Controllers\User\MobileOffer\UpdateMobileOfferController;
use App\Http\Controllers\User\MobileOfferFeature\GetMobileOfferFeaturesListController;
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
        Route::get('', [FileController::class, 'index']);
        Route::post('', [FileController::class, 'store']);
        Route::delete('{public_id}', [FileController::class, 'delete']);
        Route::get('cloudinary-presigned-url', [FileController::class, 'getCloudinaryPresignedUrl']);
        Route::get('cloudinary-presigned-urls', [FileController::class, 'getCloudinaryPresignedUrls']);
        Route::post('mobile-offer-cloudinary-notifications-url', [FileController::class, 'mobileOfferCloudinaryNotificationUrl']);
    });

Route::prefix('admins')
    ->middleware(['api'])
    ->group(function () {

        // NEEDS CSRF TOKEN, EVEN THOUGH IT'S OUTSIDE auth:sanctum middleware
        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('logout', [AuthController::class, 'logout']);
        });

    });

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

                    Route::get('get-user-phone-number', GetUserPhoneNumberController::class);
                    Route::patch('change-password', ChangePasswordController::class);
                    Route::patch('change-phone-number', ChangePhoneNumberController::class);

                });

                route::prefix('my-mobile-offers')
                    ->group(function () {
                        Route::get('', GetMyMobileOffersController::class);

                        Route::get('{id}', GetMyMobileOfferController::class);

                        Route::patch('/{id}/sold', SellMobileOfferController::class);

                        Route::patch('{id}', UpdateMobileOfferController::class);

                        Route::delete('{id}', DeleteMobileOfferController::class);

                    });

                Route::prefix('mobile-offers')
                    ->group(function () {
                        Route::get('search', SearchMobilesOffersController::class);
                        Route::get('favourites', GetFavouriteMobileOffersController::class);
                        Route::get('{id}', GetMobileOfferController::class);

                        Route::post('', CreateMobileOfferController::class);
                        Route::patch('{id}/favourite', FavouriteMobileOfferController::class);

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
