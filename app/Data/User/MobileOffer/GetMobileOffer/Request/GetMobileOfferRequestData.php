<?php

namespace App\Data\User\MobileOffer\GetMobileOffer\Request;

use App\Models\MobileOffer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class GetMobileOfferRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'usersGetMobileOfferRequestPathParameterData', // the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists(MobileOffer::class, 'id')
        ]
        public int $id,
    ) {}
}
