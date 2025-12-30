<?php

namespace App\Data\User\MobileOffer\GetMyMobileOffer\Response;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'UserMobileOfferGetMobileOfferRequestFeatuerDataGetMobileOfferResponseData')]
class FeatureData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name,
    ) {}

}
