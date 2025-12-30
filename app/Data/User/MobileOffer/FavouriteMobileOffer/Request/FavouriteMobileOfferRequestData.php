<?php

namespace App\Data\User\MobileOffer\favouriteMobileOffer\Request;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;

class favouriteMobileOfferRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'usersfavouriteMobileOfferRequestPathParameterData', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists('mobile_offers', 'id')
        ]
        public int $id,
    ) {
    }
}
