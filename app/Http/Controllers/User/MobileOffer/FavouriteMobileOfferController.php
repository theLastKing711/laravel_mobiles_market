<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\MobileOffer\favouriteMobileOffer\Request\favouriteMobileOfferRequestData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class favouriteMobileOfferController extends MobileOfferController
{
    #[OAT\Patch(path: '/users/mobile-offers/{id}/favourite', tags: ['usersMobileOffers'])]
    #[JsonRequestBody(favouriteMobileOfferRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(favouriteMobileOfferRequestData $request)
    {

        Auth::User()
            ->favouriteMobileOffers()
            ->toggle(
                [$request->id]
            );

    }
}
