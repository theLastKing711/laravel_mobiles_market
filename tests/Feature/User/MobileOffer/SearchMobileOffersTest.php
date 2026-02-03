<?php

namespace Tests\Feature\User\MobileOffer;

use App\Data\Store\MobileOffer\GetMobileOffers\Response\GetMobileOffersResponsePaginationResultData;
use App\Http\Controllers\User\MobileOffer\SearchMobilesOffersController;
use App\Models\MobileOffer;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;
use Tests\Feature\User\MobileOffer\Providers\GetMobileOffersProviderParameters;

class SearchMobileOffersTest extends UserTestCase
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

    public static function seach_mobile_offers_provider()
    {

        return [
            'with empty search name' => [
                new GetMobileOffersProviderParameters(
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
            'with empty search name and no mobile offers' => [
                new GetMobileOffersProviderParameters(
                    [],
                    '',
                    7,
                    expected_number_of_response_items: 0
                ),
            ],
            'with english search name' => [
                new GetMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'fi'],
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أبس'],
                    ],
                    'fi',
                    7,
                    expected_number_of_response_items: 2
                ),
            ],
            'with arabic search name' => [
                new GetMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name'],
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أبس'],
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
                new GetMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name'],
                        ['name_in_english' => 'fi name'],
                        ['name_in_english' => 'third name'],
                        ['name_in_arabic' => 'أبل'],
                        ['name_in_arabic' => 'أبل ستور'],
                        ['name_in_arabic' => 'شششص'],
                        ['name_in_arabic' => 'أبسش شسي'],
                        ['name_in_arabic' => 'أبسشششسي'],
                        ['name_in_arabic' => 'أبل محل'],
                        ['name_in_arabic' => 'أبلي'],

                    ],
                    'أبل',
                    per_page: 3,
                    expected_number_of_response_items: 3
                ),
            ],
        ];

    }

    #[
        Test,
        Group(SearchMobilesOffersController::class),
        Group('success'),
        Group('200'),
        DataProvider('seach_mobile_offers_provider')
    ]
    public function search_mobile_offers_success_with_200_status(GetMobileOffersProviderParameters $data_provider): void
    {

        MobileOffer::factory()
            ->forUserWithId($this->user->id)
            ->createMany(
                $data_provider->recrods
            );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.mobile-offers.search',
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
                    GetMobileOffersResponsePaginationResultData::from(
                        $response->json()
                    );

        $this
            ->assertEquals(
                min($data_provider->expected_number_of_response_items, $data_provider->per_page),
                $response_data->data->count(),
            );

    }
}
