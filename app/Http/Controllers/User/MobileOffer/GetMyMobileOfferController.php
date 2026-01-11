<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\MobileOffer\GetMyMobileOffer\Request\GetMyMobileOfferRequestData;
use App\Data\User\MobileOffer\GetMyMobileOffer\Response\GetMyMobileOfferResponseData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\MobileOffer;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class GetMyMobileOfferController extends MobileOfferController
{
    #[OAT\Get(path: '/users/my-mobile-offers/{id}', tags: ['usersMobileOffers'])]
    #[QueryParameter('is_sold', 'boolean')]
    #[
        OAT\PathItem(
            path: '/users/my-mobile-offers/{id}',
            parameters: [
                new OAT\PathParameter(
                    ref: '#/components/parameters/usersGetMyMobileOfferRequestPathParameterData',
                ),
            ],
        ),
    ]
    #[SuccessItemResponse(GetMyMobileOfferResponseData::class)]
    public function __invoke(GetMyMobileOfferRequestData $request)
    {

        return GetMyMobileOfferResponseData::from(
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
                            (select exists (select 1 from user_favourites_mobile_offer where user_id=? AND mobile_offer_id=mobile_offers.id)) as is_favourite
                        ',
                    [Auth::User()->id]
                )
                ->when(
                    $request->is_sold,
                    fn ($query) => $query->sold
                )
                ->firstWhere(
                    'id',
                    $request->id
                )
        );

    }
}
