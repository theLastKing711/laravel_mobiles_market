<?php

namespace App\Data\User\Reporting\FlagMobileOffer\Request;

use App\Models\MobileOffer;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;

class FlagMobileOfferRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'usersFlagMobileOfferRequestPathParameterData', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists(MobileOffer::class, 'id')
        ]
        public int $id,
    ) {
    }
}
