<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\MobileOffer\GetFavouriteMobileOffers\Request\GetFavouriteMobileOffersRequestData;
use App\Data\User\MobileOffer\GetFavouriteMobileOffers\Response\GetFavouriteMobileOffersResponseData;
use App\Data\User\MobileOffer\GetFavouriteMobileOffers\Response\GetFavouriteMobileOffersResponsePaginationResultData;
use App\Http\Controllers\User\MobileOffer\Abstract\MobileOfferController;
use App\Models\MobileOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class GetFavouriteMobileOffersController extends MobileOfferController
{
    #[OAT\Get(path: '/users/mobile-offers/favourites', tags: ['usersMobileOffers'])]
    #[QueryParameter('page', 'integer')]
    #[QueryParameter('perPage', 'integer')]
    #[SuccessItemResponse(GetFavouriteMobileOffersResponsePaginationResultData::class)]
    public function __invoke(GetFavouriteMobileOffersRequestData $request)
    {
        $logged_user_id =
            Auth::User()->id;

        return GetFavouriteMobileOffersResponseData::collect(
            MobileOffer::search($request->search)
                 // gets called on client side after remote query success
                ->query(
                    fn (Builder $query) => $query
                        ->with([
                            'features',
                            'mainImage',
                        ])
                        ->selectRaw(
                            '
                            *,
                            true is_favourite
                        ',
                        )
                        ->whereRelation(
                            'favouriteByUsers',
                            'user_id',
                            $logged_user_id
                        )
                )
                // also get called on client side
                ->paginate(5)
        );
    }
}
