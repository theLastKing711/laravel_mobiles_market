<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Data\Store\MobileOffer\CreateMobileOffer\Request\CreateMobileOfferRequestData;
use App\Data\Store\MobileOffer\CreateMobileOffer\Request\FeatureData;
use App\Data\User\MobileOffer\GetMyMobileOffers\Response\GetMyMobileOffersResponsePaginationResultData;
use App\Data\User\MobileOffer\UpdateMobileOffer\Request\UpdateMobileOfferRequestData;
use App\Enum\Language;
use App\Models\Media;
use App\Models\MobileFeature;
use App\Models\MobileOffer;
use App\Models\MobileOfferFeature;
use App\Models\TemporaryUploadedImages;
use Database\Seeders\MobileOfferFeatureSeeder;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;
use Tests\Feature\User\MyMobileOffer\Providers\CreateMyMobileOfferProviderParameters;
use Tests\Feature\User\MyMobileOffer\Providers\GetMyMobileOffersProviderParameters;
use Tests\Feature\User\MyMobileOffer\Providers\UpdateMyMobileOfferProviderParameters;
use Tests\TraitMocks\TranslationServiceMock;
use Tests\Traits\StoreTrait;

class MyMobileOfferTest extends UserTestCase
{
    use StoreTrait, TranslationServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->initializeStore();

        $this
            ->withRoutePaths(
                'my-mobile-offers'
            );

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
                ->withQueryParameters([
                    'search' => $data_provider->search,
                    'perPage' => $data_provider->per_page,
                ])
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

    #[
        Test,
    ]
    public function get_my_mobile_offer_success_with_200_status(): void
    {
        $mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $response =
            $this
                ->withRoutePaths(
                    $mobile_offer->id
                )
                ->getJsonData();

        $response
            ->assertStatus(
                200
            );

    }

    public static function create_my_mobile_offer_provider()
    {

        return [
            'generic use case' => [
                new CreateMyMobileOfferProviderParameters(
                    'english name',
                    'اسم عربي',
                    2,
                    2,
                ),
            ],
            'with zero features' => [
                new CreateMyMobileOfferProviderParameters(
                    'english name',
                    'اسم عربي',
                    0,
                    1,
                ),
            ],
            'with arabic name' => [
                new CreateMyMobileOfferProviderParameters(
                    'اسم عربي',
                    'english name',
                    1,
                    1,
                ),
            ],
        ];
    }

    #[
        Test,
        DataProvider('create_my_mobile_offer_provider')
    ]
    public function create_my_mobile_offer_success_with_200_status(CreateMyMobileOfferProviderParameters $data_provider): void
    {
        $this->mocKTranslate(
            $data_provider->name,
            $data_provider->name_translation
        );

        $temporary_uploaded_images =
           TemporaryUploadedImages::factory(count: $data_provider->request_number_of_images)
               ->createMany();

        $features =
            MobileOfferFeature::query()
                ->take($data_provider->request_number_of_features)
                ->get('id');

        $random_features_data =
            collect(
                $features
            )
                ->map(fn ($feature) => new FeatureData($feature->id));

        $request =
           new CreateMobileOfferRequestData(
               $data_provider->name,
               fake()->numberBetween(100, 2000),
               fake()->word(),
               fake()->word(),
               fake()->numberBetween(80, 100),

               $random_features_data,
               $temporary_uploaded_images
                   ->pluck('id')
                   ->toArray()
           );

        $response =
            $this
                ->postJsonData(
                    $request
                        ->toArray()
                );

        $response
            ->assertStatus(
                200
            );

        $created_mobile_offer =
            MobileOffer::query()
                ->firstWhere(
                    'user_id',
                    Auth::User()->id
                );

        $langauge_of_uploaded_name =
            Language::getWordLanguage($request->name_in_english);

        $this
            ->assertDatabaseHas(
                MobileOffer::class,
                [
                    'user_id' => Auth::User()->id,
                    'mobile_name_language_when_uploaded' => $langauge_of_uploaded_name,
                    'is_sold' => false,
                ]
            );

        $this
            ->assertDatabaseCount(
                MobileOffer::class,
                1
            );

        if ($request->features->count() > 0) {
            $this
                ->assertDatabaseHas(
                    MobileFeature::class,
                    [
                        'mobile_offer_feature_id' => $request->features->value('id'),
                        'mobile_offer_id' => $created_mobile_offer
                            ->id,
                    ]
                );
        }

        $this
            ->assertDatabaseCount(
                MobileFeature::class,
                $request->features->count()
            );

        $this
            ->assertDatabaseCount(
                TemporaryUploadedImages::class,
                0
            );

        if (count($request->temporary_uploaded_images_ids) > 0) {
            $this
                ->assertDatabaseHas(
                    Media::class,
                    [
                        'public_id' => $temporary_uploaded_images->value('public_id'),
                    ]
                );
        }

        $this
            ->assertDatabaseCount(
                Media::class,
                count(
                    $request->temporary_uploaded_images_ids
                )
            );

    }

    /**
     * @return array<array<UpdateMyMobileOfferProviderParameters
     **/
    public static function update_my_mobile_offer_provider()
    {

        return [
            'genereic use case' => [
                new UpdateMyMobileOfferProviderParameters(
                    2,
                    2,
                    'english name',
                    'اسم عربي',
                    2,
                    2
                ),
            ],
            'with zero old mobile offer features' => [
                new UpdateMyMobileOfferProviderParameters(
                    0,
                    1,
                    'english name',
                    'اسم عربي',
                    0,
                    1
                ),
            ],
            'with zero added images' => [
                new UpdateMyMobileOfferProviderParameters(
                    2,
                    1,
                    'english name',
                    'اسم عربي',
                    1,
                    0
                ),
            ],
            'with arabic name' => [
                new UpdateMyMobileOfferProviderParameters(
                    1,
                    1,
                    'اسم عربي',
                    'english_name',
                    1,
                    1
                ),
            ],
        ];

    }

    #[
        Test,
        DataProvider('update_my_mobile_offer_provider')
    ]
    public function update_my_mobile_offer_success_with_200_status($data_provider): void
    {

        $this->mocKTranslate(
            $data_provider->name,
            $data_provider->name_translation
        );

        $all_features_count = 5;

        $all_features =
            MobileOfferFeature::factory(count: $all_features_count)
                ->create();

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->has(
                    Media::factory(count: $data_provider->mobile_offer_old_number_of_images),
                    'medially'
                )
                ->create();

        $mobile_offer_old_features =
            MobileOfferFeature::factory($data_provider->mobile_offer_old_number_of_features)
                ->createMany();

        $new_mobile_offer
            ->features()
            ->attach(
                $mobile_offer_old_features->pluck('id')
            );

        $temporary_uploaded_images =
            TemporaryUploadedImages::factory($data_provider->request_number_of_images)
                ->create();

        $request =
           new UpdateMobileOfferRequestData(
               $data_provider->name,
               fake()->numberBetween(100, 2000),
               fake()->word(),
               fake()->word(),
               fake()->numberBetween(80, 100),

               $all_features->pluck('id')->take(limit: $data_provider->request_number_of_features)->toArray(),
               $temporary_uploaded_images
                   ->pluck('id')
                   ->toArray(),

               $new_mobile_offer->id
           );

        $response =
            $this
                ->withRoutePaths(
                    $new_mobile_offer->id
                )
                ->patchJsonData(
                    $request
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

        $langauge_of_uploaded_name =
            Language::getWordLanguage($request->name_in_english);

        $this
            ->assertDatabaseHas(
                MobileOffer::class,
                [
                    'user_id' => Auth::User()->id,
                    'mobile_name_language_when_uploaded' => $langauge_of_uploaded_name,
                    'is_sold' => false,
                ]
            );

        $this
            ->assertDatabaseCount(
                MobileFeature::class,
                count($request->features)
            );

        if (count($request->features)) {
            $this
                ->assertDatabaseHas(
                    MobileFeature::class,
                    [
                        'mobile_offer_feature_id' => array_first($request->features),
                        'mobile_offer_id' => $request->id,
                    ]
                );

        }

        $this
            ->assertDatabaseCount(
                TemporaryUploadedImages::class,
                0
            );

        $this
            ->assertDatabaseCount(
                Media::class,
                count($request->temporary_uploaded_images_ids) + $data_provider->mobile_offer_old_number_of_images
            );

    }

    #[
        Test,
    ]
    public function create_my_mobile_offer_wit_no_uploaded_images_errors_with_422_status(): void
    {
        $features =
            MobileOfferFeature::query()
                ->take(2)
                ->get('id');

        $random_features_data =
            collect(
                $features
            )
                ->map(fn ($feature) => new FeatureData($feature->id));

        $request =
           new CreateMobileOfferRequestData(
               'testing',
               fake()->numberBetween(100, 2000),
               fake()->word(),
               fake()->word(),
               fake()->numberBetween(80, 100),

               $random_features_data,
               []
           );

        $response =
            $this
                ->postJsonData(
                    $request
                        ->toArray()
                );

        $response
            ->assertStatus(
                status: 422
            );

        $response
            ->assertOnlyJsonValidationErrors(
                [
                    'temporary_uploaded_images_ids' => __(
                        'messages.users.my-mobile-offers.empty_number_of_images'
                    ),
                ]
            );

    }

    #[
        Test,
    ]
    public function update_my_mobile_offer_with_no_uploaded_images_and_no_existing_images_errors_with_422_status(): void
    {

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $request =
           new UpdateMobileOfferRequestData(
               'test name',
               fake()->numberBetween(100, 2000),
               fake()->word(),
               fake()->word(),
               fake()->numberBetween(80, 100),
               [],
               [],
               $new_mobile_offer->id
           );

        $response =
            $this
                ->withRoutePaths(
                    $new_mobile_offer->id
                )
                ->patchJsonData(
                    $request
                        ->toArray()
                );

        $response
            ->assertStatus(
                422
            );

        $response
            ->assertOnlyJsonValidationErrors(
                [
                    'temporary_uploaded_images_ids' => __(
                        'messages.users.my-mobile-offers.empty_number_of_images'
                    ),
                ]
            );

    }

    #[
        Test,
    ]
    public function sell_my_mobile_offer_success_with_200_status(): void
    {

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $response =
            $this
                ->withRoutePaths(
                    $new_mobile_offer->id,
                    'sold'
                )
                ->patchJsonData();

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

    #[
        Test,
    ]
    public function delete_my_mobile_offer_success_with_200_status(): void
    {

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->store->id)
                ->create();

        $response =
            $this
                ->withRoutePaths(
                    $new_mobile_offer->id,
                )
                ->deleteJsonData();

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
