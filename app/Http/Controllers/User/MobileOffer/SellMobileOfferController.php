<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\MobileOffer\SellMobileOffer\Request\SellMobileOfferRequestData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\MobileOffer;
use OpenApi\Attributes as OAT;

class SellMobileOfferController extends MobileOfferController
{
    #[OAT\Patch(path: '/users/my-mobile-offers/{id}', tags: ['usersMobileOffers'])]
    #[JsonRequestBody(SellMobileOfferRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(SellMobileOfferRequestData $request)
    {

        MobileOffer::query()
            ->firstWhere(
                'id',
                $request->id
            )
            ->update(
                [
                    'is_sold' => true,
                ]
            );

    }
}
