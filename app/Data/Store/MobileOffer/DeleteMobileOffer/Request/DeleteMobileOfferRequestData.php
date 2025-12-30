<?php

namespace App\Data\Store\MobileOffer\DeleteMobileOffer\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class DeleteMobileOfferRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'storesDeleteMobileOfferRequestPathParameterData', // the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists('mobile_offers', 'id')
        ]
        public int $id,
    ) {}
}
