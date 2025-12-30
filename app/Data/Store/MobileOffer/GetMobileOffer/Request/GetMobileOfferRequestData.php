<?php

namespace App\Data\Store\MobileOffer\GetMobileOffer\Request;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;

class GetMobileOfferRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'storesGetMobileOfferRequestPathParameterData', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists('mobileoffers', 'id')
        ]
        public int $id,
    ) {
    }
}
