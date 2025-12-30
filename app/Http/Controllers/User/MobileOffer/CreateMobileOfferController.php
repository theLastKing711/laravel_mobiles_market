<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\Store\MobileOffer\CreateMobileOffer\Request\CreateMobileOfferRequestData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\Media;
use App\Models\MobileOffer;
use App\Models\TemporaryUploadedImages;
use App\Services\Api\TranslationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OAT;

class CreateMobileOfferController extends MobileOfferController
{
    #[OAT\Post(path: '/users/mobile-offers', tags: ['usersMobileOffers'])]
    #[JsonRequestBody(CreateMobileOfferRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(CreateMobileOfferRequestData $request, TranslationService $translationService)
    {

        $translation_set =
            $translationService
                ->translate($request->name_in_english);

        DB::transaction(function () use ($request, $translation_set) {

            $mobile_offer =
                MobileOffer::query()
                    ->create(
                        [
                            'user_id' => Auth::User()->id,
                            'mobile_name_language_when_uploaded' => $translation_set->upload_language,
                            'name_in_english' => $translation_set->name_en,
                            'name_in_arabic' => $translation_set->name_ar,
                            'price_in_usd' => $request->price_in_usd,
                            'is_sold' => false,
                            // 'screen_size' => $request->screen_size,
                            // 'screen_type' => $request->screen_type,
                            // 'cpu' => $request->cpu,
                            'ram' => $request->ram,
                            'storage' => $request->storage,
                            // 'battery_size' => $request->battery_size,
                            'battery_health' => $request->battery_health,
                            // 'number_of_sims' => $request->number_of_sims,
                            // 'number_of_esims' => $request->number_of_esims,
                            // 'color' => $request->color,
                        ]
                    );

            $mobile_offer
                ->features()
                ->attach(
                    $request
                        ->features
                        ->pluck('id')
                );

            $medias =
                Media::fromTemporaryUploadedImagesIds(
                    $request
                        ->temporary_uploaded_images_ids
                );

            $mobile_offer
                ->medially()
                ->saveMany(
                    $medias
                );

            TemporaryUploadedImages::query()
                ->whereIn(
                    'id',
                    $request->temporary_uploaded_images_ids
                )
                ?->delete();

        });

    }
}
