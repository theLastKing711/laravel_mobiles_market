<?php

namespace Tests\Feature\User\MobileOffer;

use App\Data\Store\MobileOffer\CreateMobileOffer\Request\CreateMobileOfferRequestData;
use App\Data\Store\MobileOffer\CreateMobileOffer\Request\FeatureData;
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

class MobileOfferTest extends UserTestCase
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

    /**
     * @return array
     **/
    public static function create_mobile_offer_provider()
    {
        return [
            'with_zero_features_and_zero_images_and_language_name_is_english' => [0, 0, 'english name'],
            [2, 2, 'اسم عربي'],

        ];

    }

    #[
        Test,
        DataProvider('create_mobile_offer_provider')
    ]
    public function create_mobile_offer_success_with_200_status(int $number_of_features, int $number_of_images, string $name): void
    {
        $this
            ->initializeUser();

        $temporary_uploaded_images =
           TemporaryUploadedImages::factory(count: $number_of_images)
               ->createMany();

        $features =
            MobileOfferFeature::query()
                ->take($number_of_features)
                ->get('id');

        $random_features_data =
            collect(
                $features

            )
                ->map(fn ($feature) => new FeatureData($feature['id']));

        $request =
           new CreateMobileOfferRequestData(
               $name,
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
                ->withRoutePaths(
                    'mobile-offers'
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
                    'mobile_name_language_when_uploaded' => $langauge_of_uploaded_name,
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
     * @return array
     **/
    public static function update_mobile_offer_provider()
    {
        return [
            'with_zero_features_and_zero_images_and_language_name_is_english' => [0, 0, 'english name'],
            [2, 2, 'اسم عربي'],

        ];

    }

    #[
        Test,
        DataProvider('create_mobile_offer_provider')
    ]
    public function update_mobile_offer_success_with_200_status(int $number_of_features, int $number_of_images, string $name): void
    {
        $this
            ->initializeUser();

        $new_mobile_offer =
            MobileOffer::factory()
                ->forUserWithId($this->user->id)
                ->has(
                    MobileOfferFeature::factory($number_of_features),
                    'features'
                )
                ->has(
                    Media::factory($number_of_images),
                    'medially'
                )
                ->create();

        $request =
           new UpdateMobileOfferRequestData(
               $name,
               fake()->numberBetween(100, 2000),
               fake()->word(),
               fake()->word(),
               fake()->numberBetween(80, 100),

               $new_mobile_offer
                   ->features
                   ->take($number_of_features)
                   ->pluck('id')
                   ->toArray(),
               $new_mobile_offer
                   ->medially
                   ->take($number_of_images)
                   ->pluck('id')
                   ->toArray(),

               $new_mobile_offer->id
           );

        $response =
            $this
                ->withRoutePaths(
                    'my-mobile-offers',
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

    }
}
