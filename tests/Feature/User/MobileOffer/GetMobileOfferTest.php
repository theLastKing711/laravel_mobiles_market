<?php

namespace Tests\Feature\User\MobileOffer;

use App\Http\Controllers\User\MobileOffer\GetMobileOfferController;
use App\Models\MobileOffer;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class GetMobileOfferTest extends UserTestCase
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
        Group(GetMobileOfferController::class),
        Group('success'),
        Group('200'),
        Group('GetMobileOfferController'),
    ]
    public function get_mobile_offer_success_with_200_status(): void
    {
        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->user->id)
                ->create();

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.mobile-offers.{id}',
                        [
                            'id' => $mobile_offer->id,
                        ]
                    )
                )
                ->getJsonData();

        $response
            ->assertStatus(
                200
            );

    }
}
