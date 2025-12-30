<?php

namespace App\Http\Controllers\User\MobileOffer;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\MobileOffer\SearchMobilesOffers\Request\SearchMobilesOffersRequestData;
use App\Data\User\MobileOffer\SearchMobilesOffers\Response\SearchMobilesOffersResponseData;
use App\Data\User\MobileOffer\SearchMobilesOffers\Response\SearchMobilesOffersResponsePaginationResultData;
use App\Http\Controllers\Controller;
use App\Models\MobileOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class SearchMobilesOffersController extends Controller
{
    #[OAT\Get(path: '/users/mobile-offers/search', tags: ['usersMobileOffers'])]

    #[QueryParameter('search')]
    #[QueryParameter('page', 'integer')]
    #[QueryParameter('perPage', 'integer')]
    #[SuccessItemResponse(SearchMobilesOffersResponsePaginationResultData::class)]
    public function __invoke(SearchMobilesOffersRequestData $request)
    {
        $request_search =
            $request
                ->search;

        $remote_mobiles_offers_search =
            MobileOffer::search(
                $request_search
            )
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
                            (select exists (select 1 from user_favourites_mobile_offer where user_id=? AND mobile_offer_id=mobile_offers.id)) as is_favourite
                        ',
                            [Auth::User()->id]
                        )
                )
    // also get called on client side
                ->paginate(5);

        return SearchMobilesOffersResponseData::collect(
            $remote_mobiles_offers_search
        );
    }
}
