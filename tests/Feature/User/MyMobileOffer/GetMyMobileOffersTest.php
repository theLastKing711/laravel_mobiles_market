<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Data\User\MobileOffer\GetMyMobileOffers\Response\GetMyMobileOffersResponsePaginationResultData;
use App\Http\Controllers\User\MobileOffer\GetMyMobileOffersController;
use App\Models\MobileOffer;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\Feature\User\MyMobileOffer\Providers\GetMyMobileOffersProviderParameters;

class GetMyMobileOffersTest extends StoreTestCase
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

    public static function get_my_mobile_offers_provider()
    {

        return [
            'with empty search name' => [
                new GetMyMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name'],
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أبس'],
                    ],
                    '',
                    7,
                    expected_number_of_response_items: 4
                ),
            ],
            'with english search name' => [
                new GetMyMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'fi'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أبس'],
                    ],
                    'fi',
                    7,
                    expected_number_of_response_items: 2
                ),
            ],
            'with arabic search name' => [
                new GetMyMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name'],
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أب س'],
                        ['name_in_arabic' => 'ششش'],
                        ['name_in_arabic' => 'شششص'],
                        ['name_in_arabic' => 'أبسش شسي'],
                        ['name_in_arabic' => 'أبسشششسي'],
                    ],
                    'أب',
                    7,
                    expected_number_of_response_items: 3
                ),
            ],
            'with per page less than matching items in database' => [
                new GetMyMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name'],
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أبشس'],
                        ['name_in_arabic' => 'أبل ش'],
                        ['name_in_arabic' => 'أبل'],
                        ['name_in_arabic' => 'ششش'],
                        ['name_in_arabic' => 'شششص'],
                        // ['name_in_arabic' => 'أبسش شسي'],
                        // ['name_in_arabic' => 'أبسشششسي'],
                    ],
                    search: 'أبل',
                    per_page: 2,
                    expected_number_of_response_items: 2
                ),
            ],
        ];

    }

    #[
        Test,
        Group('my-mobile-offers'),
        Group(GetMyMobileOffersController::class),
        Group('success'),
        Group('200'),
        DataProvider('get_my_mobile_offers_provider')
    ]
    public function get_my_mobile_offers_success_with_200_status(GetMyMobileOffersProviderParameters $data_provider): void
    {

        MobileOffer::factory()
            ->forUserWithId($this->store->id)
            ->createMany(
                $data_provider->recrods
            );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers',
                        [
                            'search' => $data_provider->search,
                            'perPage' => $data_provider->per_page,
                        ]
                    )
                )
                ->getJsonData();

        $response
            ->assertStatus(
                200
            );

        $response_data =
                    GetMyMobileOffersResponsePaginationResultData::from(
                        $response->json()
                    );

        $this
            ->assertEquals(
                min($data_provider->expected_number_of_response_items, $data_provider->per_page),
                $response_data->data->count(),
            );

    }
}
