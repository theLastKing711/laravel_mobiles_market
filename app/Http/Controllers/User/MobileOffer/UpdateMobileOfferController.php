<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\MobileOffer\UpdateMobileOffer\Request\UpdateMobileOfferRequestData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\Media;
use App\Models\MobileOffer;
use App\Services\Api\TranslationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OAT;

class UpdateMobileOfferController extends MobileOfferController
{
    #[OAT\Patch(path: '/users/mobile-offers/{id}', tags: ['storesMobileOffers'])]
    #[JsonRequestBody(UpdateMobileOfferRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(UpdateMobileOfferRequestData $request, TranslationService $translationService)
    {

        $translation_set =
           $translationService
               ->translate($request->name_in_english);

        DB::transaction(function () use ($request, $translation_set) {

            $mobile_offer =
                MobileOffer::query()
                    ->firstWhere(
                        'id',
                        $request->id
                    );

            $mobile_offer
                ->update(
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
                ->sync(
                    $request->features
                );

            $temporary_uploaded_images_ids =
                $request
                    ->temporary_uploaded_images_ids
                    ->pluck('id');

            $medias =
                Media::createFromTemporaryUploadedImagesIds(
                    $temporary_uploaded_images_ids
                );

            $mobile_offer
                ->medially()
                ->saveMany(
                    $medias
                );

        });

    }
}
