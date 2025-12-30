<?php

namespace App\Data\Store\MobileOffer\GetMobileOffers\Response;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use OpenApi\Attributes as OAT;

#[TypeScript]
#[Oat\Schema(schema: 'StoreMobileOfferGetMobileOffersResponseGetMobileOffersResponseData')]
class GetMobileOffersResponseData extends Data
{
    public function __construct(

    ) {
    }
}
