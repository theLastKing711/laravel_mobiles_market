<?php

namespace App\Data\User\MobileOfferFeature\GetMobileOfferFeaturesList\Response;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'UserMobileOfferFeatureGetMobileOfferFeaturesListResponseGetMobileOfferFeaturesListResponseData')]
class GetMobileOfferFeaturesListResponseData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name,
    ) {}

}
