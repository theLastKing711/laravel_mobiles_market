<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Http\Controllers\User\MobileOffer\GetMyMobileOfferController;
use App\Models\MobileOffer;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\TranslationServiceMock;

class GetMyMobileOfferTest extends StoreTestCase
{
    use TranslationServiceMock;

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
        Group(GetMyMobileOfferController::class),
        Group('success'),
        Group('200')
    ]
    public function get_my_mobile_offer_success_with_200_status(): void
    {
        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.{id}.get',
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
