<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Http\Controllers\User\MobileOffer\DeleteMobileOfferController;
use App\Models\MobileOffer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;

class DeleteMobileOfferTest extends StoreTestCase
{
    #[
        Test,
        Group('my-mobile-offers'),
        Group(DeleteMobileOfferController::class),
        Group('success'),
        Group('200'),
    ]
    public function delete_my_mobile_offer_success_with_200_status(): void
    {

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.{id}.delete',
                        [
                            'id' => $new_mobile_offer->id,
                        ]
                    )
                )
                ->deleteJsonData(
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
                0
            );

    }
}
