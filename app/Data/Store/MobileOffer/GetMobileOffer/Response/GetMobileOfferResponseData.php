<?php

namespace App\Data\Store\MobileOffer\GetMobileOffer\Response;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use OpenApi\Attributes as OAT;

#[TypeScript]
#[Oat\Schema(schema: 'StoreMobileOfferGetMobileOfferResponseGetMobileOfferResponseData')]
class GetMobileOfferResponseData extends Data
{
    public function __construct(

    ) {}

}
