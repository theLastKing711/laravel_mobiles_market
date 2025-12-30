<?php

namespace App\Data\User\MobileOffer\GetMobileOffer\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'UserMyMobileOfferGetMobileOfferRequestFeatuerDataGetMobileOfferResponseData')]
class FeatureData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name,
    ) {}
}
