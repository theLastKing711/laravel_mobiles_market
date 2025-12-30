<?php

namespace App\Data\User\MobileOffer\GetMyMobileOffer\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class GetMyMobileOfferRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public ?string $is_sold,
        #[
            OAT\PathParameter(
                parameter: 'usersGetMyMobileOfferRequestPathParameterData', // the name used in ref
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
