<?php

namespace App\Data\Store\MobileOffer\CreateMobileOffer\Request;

use App\Models\MobileOfferFeature;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'UserMobileOfferCreateMobileOfferRequestCreateMobileOfferRequestDataFeatureData')]
class FeatureData extends Data
{
    public function __construct(
        #[
            OAT\Property,
            Exists(MobileOfferFeature::class, 'id')
        ]
        public int $id,
    ) {}

}
