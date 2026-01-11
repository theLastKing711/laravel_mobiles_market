<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\MobileOffer\GetMyMobileOffers\Request\GetMyMobileOffersRequestData;
use App\Data\User\MobileOffer\GetMyMobileOffers\Response\GetMyMobileOffersResponseData;
use App\Data\User\MobileOffer\GetMyMobileOffers\Response\GetMyMobileOffersResponsePaginationResultData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\MobileOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class GetMyMobileOffersController extends MobileOfferController
{
    #[OAT\Get(path: '/users/my-mobile-offers', tags: ['usersMobileOffers'])]
    #[QueryParameter('search')]
    #[QueryParameter('page', 'integer')]
    #[QueryParameter('perPage', 'integer')]
    #[SuccessItemResponse(GetMyMobileOffersResponsePaginationResultData::class)]
    public function __invoke(GetMyMobileOffersRequestData $request)
    {

        $request_search =
            $request
                ->search;

        $remote_mobiles_offers_search =
            MobileOffer::search(
                $request_search
            )
                // ->where(
                //     'user_id',
                //     Auth::User()->id
                // )
    // gets called on client side after remote query success
                ->query(
                    fn (Builder $query) => $query
                        ->selectRaw(
                            '
                                    *,
                                    (select exists (select 1 from user_favourites_mobile_offer where user_id=? AND mobile_offer_id=mobile_offers.id)) as is_favourite
                                ',
                            [Auth::User()->id]
                        )
                        ->with([
                            'features',
                            'mainImage',
                        ])
                )
    // also get called on client side after remote query success
                ->paginate(7);

        // return $remote_mobiles_offers_search->total();

        return GetMyMobileOffersResponseData::collect(
            $remote_mobiles_offers_search
        );
    }
}
