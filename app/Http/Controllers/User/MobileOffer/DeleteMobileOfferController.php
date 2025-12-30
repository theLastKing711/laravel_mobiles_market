<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\Store\MobileOffer\DeleteMobileOffer\Request\DeleteMobileOfferRequestData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\MobileOffer;
use OpenApi\Attributes as OAT;

class DeleteMobileOfferController extends MobileOfferController
{
    #[OAT\Delete(path: '/users/my-mobile-offers/{id}', tags: ['usersMobileOffers'])]
    #[SuccessNoContentResponse]
    public function __invoke(DeleteMobileOfferRequestData $request)
    {
        MobileOffer::query()
            ->firstWhere(
                'id',
                $request->id
            )
            ->delete();
    }
}
