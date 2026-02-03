<?php

namespace Tests\Feature\User\MobileOffer;

use App\Http\Controllers\User\MobileOffer\FavouriteMobileOfferController;
use App\Models\MobileOffer;
use App\Models\UserFavouritesMobileOffer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class FavouriteMobileOfferTest extends UserTestCase
{
    #[
        Test,
        Group(FavouriteMobileOfferController::class),
        Group('success'),
        Group('200'),
    ]
    public function favourite_mobile_offer_success_with_200_status(): void
    {

        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->user->id)
                ->create();

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.mobile-offers.{id}.favourite',
                        [
                            'id' => $mobile_offer->id,
                        ]
                    )
                )
                ->patchJsonData();

        $response
            ->assertStatus(
                200
            );

        $this
            ->assertDatabaseCount(
                UserFavouritesMobileOffer::class,
                1
            );

        $this
            ->assertDatabaseHas(
                UserFavouritesMobileOffer::class,
                [
                    'user_id' => $this->user->id,
                    'mobile_offer_id' => $mobile_offer->id,
                ]
            );

    }

    #[
        Test,
        Group(FavouriteMobileOfferController::class),
        Group('success'),
        Group('200'),
    ]
    public function unfavourite_mobile_offer_success_with_200_status(): void
    {

        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->user->id)
                ->create();

        $this
            ->user
            ->favouriteMobileOffers()
            ->attach($mobile_offer->id);

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.mobile-offers.{id}.favourite',
                        [
                            'id' => $mobile_offer->id,
                        ]
                    )
                )
                ->patchJsonData();

        $response
            ->assertStatus(
                200
            );

        $this
            ->assertDatabaseCount(
                UserFavouritesMobileOffer::class,
                0
            );

    }
}
