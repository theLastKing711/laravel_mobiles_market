<?php

namespace App\Data\User\MobileOffer\UpdateMobileOffer\Request;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class UpdateMobileOfferRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public ?string $name_in_english,
        #[OAT\Property]
        public ?int $price_in_usd,
        #[OAT\Property]
        public ?string $ram,
        #[OAT\Property]
        public ?string $storage,
        #[OAT\Property]
        public ?int $battery_health,
        /** @var array<int> */
        public ?array $features,
        #[ArrayProperty()]
        /** @var array<int> */
        public ?array $temporary_uploaded_images_ids,
        #[
            OAT\PathParameter(
                parameter: 'storesUpdateMobileOfferRequestPathParameterData', // the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
        ]
        public int $id,
    ) {}
}
