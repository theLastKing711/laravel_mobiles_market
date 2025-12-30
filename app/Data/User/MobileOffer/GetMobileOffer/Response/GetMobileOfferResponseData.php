<?php

namespace App\Data\User\MobileOffer\GetMobileOffer\Response;

use App\Data\Shared\Media\MediaData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\User\MobileOffer\GetMobileOffer\Request\FeatureData;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'UserMobileOfferGetMobileOfferResponseGetMobileOfferResponseData')]
class GetMobileOfferResponseData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name_in_english,
        #[OAT\Property]
        public string $name_in_arabic,
        #[OAT\Property]
        public ?int $price_in_usd,
        #[OAT\Property]
        public ?string $ram,
        #[OAT\Property]
        public ?string $storage,
        #[OAT\Property]
        public ?int $battery_health,

        #[ArrayProperty(FeatureData::class)]
        /** @var Collection<FeatureData> */
        public Collection $features,

        #[
            ArrayProperty(MediaData::class),
            MapInputName('medially')
        ]
        /** @var Collection<MediaData> */
        public Collection $images,
        #[OAT\Property]
        public string $phone_number,
        // #[
        //     OAT\Property,
        // ]
        // public bool $is_favourite,
    ) {}

}
