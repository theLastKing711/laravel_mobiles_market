<?php

namespace Tests\Feature\User\MyMobileOffer;

use App\Data\Shared\File\TemporaryUploadedImagesData;
use App\Data\User\MobileOffer\UpdateMobileOffer\Request\UpdateMobileOfferRequestData;
use App\Enum\Language;
use App\Http\Controllers\User\MobileOffer\UpdateMobileOfferController;
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
use Tests\Feature\User\MyMobileOffer\Providers\UpdateMyMobileOfferProviderParameters;
use Tests\TraitMocks\TranslationServiceMock;

class UpdateMobileOfferTest extends StoreTestCase
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

    /**
     * @return array<array<UpdateMyMobileOfferProviderParameters>>
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
        Group('my-mobile-offers'),
        Group(UpdateMobileOfferController::class),
        Group('success'),
        Group('200'),
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
                   ->map(fn ($item) => new TemporaryUploadedImagesData($item->id)),

               $new_mobile_offer->id
           );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.{id}.patch',
                        [
                            'id' => $new_mobile_offer->id,
                        ]
                    )
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
        Group('my-mobile-offers'),
        Group(UpdateMobileOfferController::class),
        Group('success'),
        Group('200'),

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
               collect([])
                   ->map(fn ($item) => new TemporaryUploadedImagesData($item->id)),

               $new_mobile_offer->id
           );

        $response =
            $this
                ->withRouteName(
                    route(
                        'users.my-mobile-offers.{id}.patch',
                        [
                            'id' => $new_mobile_offer->id,
                        ]
                    )
                )
                ->patchJsonData(
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
