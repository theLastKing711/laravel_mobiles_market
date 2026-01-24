<?php

namespace App\Data\Store\MobileOffer\CreateMobileOffer\Request;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
#[Oat\Schema(schema: 'UserMobileOfferCreateMobileOfferRequestCreateMobileOfferRequestData')]
class CreateMobileOfferRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $name_in_english,
        #[
            OAT\Property,
            GreaterThan(0)
        ]
        public int $price_in_usd,
        // #[OAT\Property]
        // public ?string $screen_size,
        // #[OAT\Property]
        // public ?string $screen_type,
        // #[OAT\Property]
        // public ?string $cpu,
        #[OAT\Property]
        public ?string $ram,
        #[
            OAT\Property,
        ]
        public ?string $storage,
        // #[OAT\Property]
        // public ?int $battery_size,
        #[
            OAT\Property,
            Between(0, 100)
        ]
        public int $battery_health,
        // #[OAT\Property]
        // public ?int $number_of_sims,
        // #[OAT\Property]
        // public ?int $number_of_esims,
        // #[OAT\Property]
        // public ?int $color,
        #[
            ArrayProperty(FeatureData::class),
        ]
        /** @var Collection<FeatureData> */
        public ?Collection $features,
        #[
            ArrayProperty(),
        ]
        /** @var array<int> */
        public array $temporary_uploaded_images_ids,
    ) {}

}
