<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\MobileOffer\FavouriteMobileOffer\Request\FavouriteMobileOfferRequestData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class FavouriteMobileOfferController extends MobileOfferController
{
    #[OAT\Patch(path: '/users/mobile-offers/{id}/favourite', tags: ['usersMobileOffers'])]
    #[JsonRequestBody(FavouriteMobileOfferRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(FavouriteMobileOfferRequestData $request): void
    {

        Auth::User()
            ->favouriteMobileOffers()
            ->toggle(
                [$request->id]
            );

    }
}
