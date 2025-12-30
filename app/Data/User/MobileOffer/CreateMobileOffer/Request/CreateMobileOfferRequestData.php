<?php

namespace App\Data\User\MobileOffer\CreateMobileOffer\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'StoreMobileOfferCreateMobileOfferRequestCreateMobileOfferRequestData')]
class CreateMobileOfferRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public ?string $name,
        #[OAT\Property]
        public ?int $price_in_usd,
        // #[OAT\Property]
        // public ?string $screen_size,
        // #[OAT\Property]
        // public ?string $screen_type,
        // #[OAT\Property]
        // public ?string $cpu,
        #[OAT\Property]
        public ?string $ram,
        #[OAT\Property]
        public ?string $storage,
        // #[OAT\Property]
        // public ?int $battery_size,
        #[OAT\Property]
        public ?int $battery_health,
        // #[OAT\Property]
        // public ?int $number_of_sims,
        // #[OAT\Property]
        // public ?int $number_of_esims,
        // #[OAT\Property]
        // public ?int $color,
    ) {}

}
