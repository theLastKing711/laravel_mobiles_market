<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Data\Shared\File\TemporaryUploadedImagesData;
use App\Data\Store\MobileOffer\CreateMobileOffer\Request\CreateMobileOfferRequestData;
use App\Data\Store\MobileOffer\CreateMobileOffer\Request\FeatureData;
use App\Enum\Language;
use App\Http\Controllers\User\MobileOffer\CreateMobileOfferController;
use App\Models\Media;
use App\Models\MobileFeature;
use App\Models\MobileOffer;
use App\Models\MobileOfferFeature;
use App\Models\TemporaryUploadedImages;
use Database\Seeders\MobileOfferFeatureSeeder;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\Feature\User\MyMobileOffer\Providers\CreateMyMobileOfferProviderParameters;
use Tests\TraitMocks\TranslationServiceMock;

class CreateMobileOfferTest extends StoreTestCase
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
        Group('my-mobile-offers'),
        Group(CreateMobileOfferController::class),
        Group('success'),
        Group('200'),
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
               TemporaryUploadedImagesData::collect(
                   $temporary_uploaded_images
               )
           );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.post',
                    )
                )
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

        $this
            ->assertDatabaseCount(
                Media::class,
                count(
                    $request->temporary_uploaded_images_ids
                )
            );

        $this
            ->assertDatabaseHas(
                Media::class,
                [
                    'public_id' => $temporary_uploaded_images->value('public_id'),
                ]
            );

    }

    #[
        Test,
        Group('my-mobile-offers'),
        Group(CreateMobileOfferController::class),
        Group('error'),
        Group('422'),
    ]
    public function create_my_mobile_offer_with_no_uploaded_images_errors_with_422_status(): void
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
               collect([])
           );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.post',
                    )
                )
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
}
