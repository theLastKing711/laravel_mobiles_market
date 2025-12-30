<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\MobileOffer\GetMobileOffer\Request\GetMobileOfferRequestData;
use App\Data\User\MobileOffer\GetMobileOffer\Response\GetMobileOfferResponseData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\MobileOffer;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class GetMobileOfferController extends MobileOfferController
{
    #[OAT\Get(path: '/users/mobile-offers/{id}', tags: ['usersMobileOffers'])]
    #[SuccessItemResponse(GetMobileOfferResponseData::class)]
    public function __invoke(GetMobileOfferRequestData $request)
    {

        return GetMobileOfferResponseData::from(
            MobileOffer::query()
                ->with(
                    relations: [
                        'features',
                        'medially',
                    ]
                )
                ->selectRaw(
                    '
                            *,
                            (select exists (select 1 from user_favourites_mobile_offer where user_id=? AND mobile_offer_id=mobile_offers.id)) as is_favourite,
                            (select phone_number from users where users.id=mobile_offers.user_id) as phone_number
                        ',
                    [Auth::User()->id]
                )
                ->firstWhere(
                    'id',
                    $request->id
                )
        );

    }
}
