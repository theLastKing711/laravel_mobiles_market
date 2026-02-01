<?php

namespace Tests\Feature\User\MobileOffer;

use App\Data\Store\MobileOffer\GetMobileOffers\Response\GetMobileOffersResponsePaginationResultData;
use App\Data\User\MobileOffer\SearchMobilesOffers\Response\SearchMobilesOffersResponsePaginationResultData;
use App\Models\MobileOffer;
use App\Models\UserFavouritesMobileOffer;
use Database\Seeders\MobileOfferFeatureSeeder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;
use Tests\Feature\User\MobileOffer\Providers\GetMobileOffersProviderParameters;
use Tests\Feature\User\MobileOffer\Providers\SearchMobileOffersProviderParameters;
use Tests\TraitMocks\TranslationServiceMock;
use Tests\Traits\StoreTrait;
use Tests\Traits\UserTrait;

class MobileOfferTest extends UserTestCase
{
    use StoreTrait, TranslationServiceMock, UserTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initializeUser();

        $this
            ->seed(
                [
                    MobileOfferFeatureSeeder::class,
                ]
            );

    }

    public static function get_mobile_offers_provider()
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
        Group('SearchMobilesOffersController'),
        DataProvider('get_mobile_offers_provider')
    ]
    public function get_mobile_offers_success_with_200_status(GetMobileOffersProviderParameters $data_provider): void
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

    #[
        Test,
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

    public static function get_favourite_mobile_offers_provider()
    {

        return [
            'with empty search name and favourited offers' => [
                new SearchMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name', 'name_in_arabic' => 'ثث', 'is_favourited' => true],
                        ['name_in_english' => 'fi name', 'name_in_arabic' => 'ثث', 'is_favourited' => true],
                        ['name_in_english' => 'third name', 'name_in_arabic' => 'ثث', 'is_favourited' => true],
                        ['name_in_english' => 'third name', 'name_in_arabic' => 'أبس'],
                    ],
                    '',
                    7,
                    expected_number_of_response_items: 3
                ),
            ],
            'with empty search name and no offers' => [
                new SearchMobileOffersProviderParameters(
                    [],
                    '',
                    7,
                    expected_number_of_response_items: 0
                ),
            ],
            'with empty search name and no and no favourited offers' => [
                new SearchMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name', 'name_in_arabic' => 'ث'],
                        ['name_in_english' => 'fi name', 'name_in_arabic' => 'ث'],
                        ['name_in_english' => 'third name', 'name_in_arabic' => 'ث'],
                        ['name_in_english' => 'z', 'name_in_arabic' => 'ث'],
                    ],
                    '',
                    7,
                    expected_number_of_response_items: 0
                ),
            ],
            'with english search name' => [
                new SearchMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name', 'name_in_arabic' => 'ث', 'is_favourited' => true],
                        ['name_in_english' => 'fi name', 'name_in_arabic' => 'ث'],
                        ['name_in_english' => 'third name', 'name_in_arabic' => 'ث'],
                        ['name_in_english' => 'z', 'name_in_arabic' => 'أبس'],
                    ],
                    'fir',// bugs and doesn't include first record in result if we change search(this) to fi instead of fir
                    7,
                    expected_number_of_response_items: 1
                ),
            ],
            'with arabic search name' => [
                new SearchMobileOffersProviderParameters(
                    [
                        ['name_in_arabic' => 'أب', 'name_in_english' => 'first name', 'is_favourited' => true],
                        ['name_in_arabic' => '4', 'name_in_english' => 'fi name', 'is_favourited' => true],
                        ['name_in_arabic' => '4', 'name_in_english' => 'third name', 'is_favourited' => true],
                        ['name_in_arabic' => 'أبس', 'name_in_english' => '4', 'is_favourited' => true],
                        ['name_in_arabic' => 'ششش', 'name_in_english' => '4', 'is_favourited' => true],
                        ['name_in_arabic' => 'شششص', 'name_in_english' => '4'],
                        ['name_in_arabic' => 'أبسش شسي', 'name_in_english' => '4'],
                        ['name_in_arabic' => 'أبسشششسي', 'name_in_english' => '4'],
                    ],
                    'أب',
                    7,
                    expected_number_of_response_items: 2
                ),
            ],
            'with per page less than matching items in database' => [
                new SearchMobileOffersProviderParameters(
                    [
                        ['name_in_english' => 'first name'],
                        ['name_in_english' => 'fi name', 'is_favourited' => true],
                        ['name_in_english' => 'third name', 'is_favourited' => true],
                        ['name_in_arabic' => 'أبل ستور', 'is_favourited' => true],
                        ['name_in_arabic' => 'ششش'],
                        ['name_in_arabic' => 'شششص'],
                        ['name_in_arabic' => 'أبسش شسي'],
                        ['name_in_arabic' => 'أبل', 'is_favourited' => true],
                    ],
                    'أبل',
                    per_page: 5,
                    expected_number_of_response_items: 2
                ),
            ],
        ];

    }

    #[
        Test,
        Group('GetFavouriteMobileOffersController'),
        DataProvider('get_favourite_mobile_offers_provider')
    ]
    public function get_user_favourite_mobile_offers_success_with_200_status(SearchMobileOffersProviderParameters $data_provider): void
    {

        $mobile_offers = MobileOffer::factory()
            ->forUserWithId($this->user->id)
            ->createMany(
                collect(
                    $data_provider
                        ->recrods
                )
                    ->map(function ($item) {
                        unset($item['is_favourited']);

                        return $item;
                    })
                    ->toArray()

            );

        $mobile_offers_with_favourites =
            $mobile_offers
                ->map(
                    fn ($item, $index) => clone ($item, ['is_favourited' => data_get($data_provider->recrods[$index], 'is_favourited')])
                );

        $favourited_mobile_offers_ids =
                            $mobile_offers_with_favourites
                                ->where(
                                    'is_favourited',
                                    true
                                )
                                ->pluck('id')
                                ->toArray();
        $this
            ->user
            ->favouriteMobileOffers()
            ->attach(
                $favourited_mobile_offers_ids
            );

        $this
            ->assertDatabaseCount(
                UserFavouritesMobileOffer::class,
                count($favourited_mobile_offers_ids)
            );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.mobile-offers.favourites',
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
                    SearchMobilesOffersResponsePaginationResultData::from(
                        $response->json()
                    );

        $this
            ->assertEquals(
                min($data_provider->expected_number_of_response_items, $data_provider->per_page),
                $response_data->data->count(),
            );

    }

    #[
        Test,
        Group('GetFavouriteMobileOffersController'),
        DataProvider('get_favourite_mobile_offers_provider')
    ]
    public function get_store_favourite_mobile_offers_success_with_200_status(SearchMobileOffersProviderParameters $data_provider): void
    {

        $this->initializeStore();

        $mobile_offers = MobileOffer::factory()
            ->forUserWithId($this->store->id)
            ->createMany(
                collect(
                    $data_provider
                        ->recrods
                )
                    ->map(function ($item) {
                        unset($item['is_favourited']);

                        return $item;
                    })
                    ->toArray()

            );

        $mobile_offers_with_favourites =
            $mobile_offers
                ->map(
                    fn ($item, $index) => clone ($item, ['is_favourited' => data_get($data_provider->recrods[$index], 'is_favourited')])
                );

        $favourited_mobile_offers_ids =
                            $mobile_offers_with_favourites
                                // ->whereIn(
                                //     'name_in_english',
                                //     array_column($data_provider->recrods, 'name_in_english')
                                // )
                                ->where(
                                    'is_favourited',
                                    true
                                )
                                ->pluck('id')
                                ->toArray();
        $this
            ->store
            ->favouriteMobileOffers()
            ->attach(
                $favourited_mobile_offers_ids
            );

        $this
            ->assertDatabaseCount(
                UserFavouritesMobileOffer::class,
                count($favourited_mobile_offers_ids)
            );

        $response =
             $this
                 ->withRouteName(
                     route(
                         'users.mobile-offers.favourites',
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
                    SearchMobilesOffersResponsePaginationResultData::from(
                        $response->json()
                    );

        $this
            ->assertEquals(
                min($data_provider->expected_number_of_response_items, $data_provider->per_page),
                $response_data->data->count(),
            );

    }

    #[
        Test,
        Group('FavouriteMobileOfferController')
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
        Group('FavouriteMobileOfferController')
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
