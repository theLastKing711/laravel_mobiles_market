<?php

namespace App\Data\Store\MobileOffer\CreateMobileOffer\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'UserMobileOfferCreateMobileOfferRequestCreateMobileOfferRequestDataFeatureData')]
class FeatureData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
    ) {}

}
