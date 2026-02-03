<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Http\Controllers\User\MobileOffer\SellMobileOfferController;
use App\Models\MobileOffer;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;

class SellMobileOfferTest extends StoreTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->seed(
                [
                    MobileOfferFeatureSeeder::class,
                ]
            );

    }

    #[
        Test,
        Group('my-mobile-offers'),
        Group(SellMobileOfferController::class),
        Group('success'),
        Group('200'),
    ]
    public function sell_my_mobile_offer_success_with_200_status(): void
    {

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.{id}.sold',
                        [
                            'id' => $new_mobile_offer->id,
                        ]
                    )
                )
                ->patchJsonData(
                    $new_mobile_offer
                        ->toArray()
                );

        $response
            ->assertStatus(
                200
            );

        $this
            ->assertDatabaseCount(
                MobileOffer::class,
                1
            );

        $this
            ->assertDatabaseHas(
                MobileOffer::class,
                [
                    'user_id' => $this->store->id,
                    'is_sold' => true,
                ]
            );

    }
}
