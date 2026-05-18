<?php

namespace App\Http\Controllers\User\Reporting;


use App\Http\Controllers\User\Reporting\Abstract\FlagMobileOfferAbstractController;
use App\Models\Reporting;
use App\Data\User\Reporting\FlagMobileOffer\Request\FlagMobileOfferRequestData;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class FlagMobileOfferController extends FlagMobileOfferAbstractController
{

    #[OAT\Patch(path: '/users/reportings/flag-mobile-offer/{id}', tags: ['usersReportings'])]
    #[JsonRequestBody(FlagMobileOfferRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke( FlagMobileOfferRequestData $request)
    {

        Auth
            ::user()
            ->flagsMobileOffers()
            ->attach($request->id);

    }
}
