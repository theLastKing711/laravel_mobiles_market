<?php

namespace App\Http\Controllers\User\MobileOfferFeature;

use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\User\MobileOfferFeature\GetMobileOfferFeaturesList\Response\GetMobileOfferFeaturesListResponseData;
use App\Http\Controllers\Controller;
use App\Models\MobileOfferFeature;
use OpenApi\Attributes as OAT;

class GetMobileOfferFeaturesListController extends Controller
{
    #[OAT\Get(path: '/users/mobile-offer-features', tags: ['usersMobileOfferFeatures'])]
    #[SuccessListResponse(GetMobileOfferFeaturesListResponseData::class)]
    public function __invoke()
    {
        return GetMobileOfferFeaturesListResponseData::collect(
            MobileOfferFeature::query()
                ->get()
        );
    }
}
