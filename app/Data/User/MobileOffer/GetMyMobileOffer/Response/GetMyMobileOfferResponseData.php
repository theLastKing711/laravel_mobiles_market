<?php

namespace App\Data\User\MobileOffer\GetMyMobileOffer\Response;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Store\MobileOffer\CreateMobileOffer\Request\FeatureData;
use App\Models\Media;
use App\Models\MobileOffer;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Str;

#[TypeScript]
#[Oat\Schema(schema: 'UserMobileOfferGetMyMobileOfferResponseGetMyMobileOfferResponseData')]
class GetMyMobileOfferResponseData extends Data
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
        #[
            OAT\Property,
        ]
        public bool $is_favourite,

        #[ArrayProperty(FeatureData::class)]
        /** @var Collection<FeatureData> */
        public Collection $features,

        #[ArrayProperty(Media::class)]
        /** @var Collection<Media> */
        public Collection $medially,
    ) {}

    public static function fromModel(MobileOffer $mobile_offer, string $search): self
    {
        $name =
            static::contains_arabic(
                $search
            )
            ?
            $mobile_offer->name_in_arabic
            :
            $mobile_offer->name_in_english;

        return new self(
            $mobile_offer->id,
            $name,
            $name,
            $mobile_offer->price_in_usd,
            $mobile_offer->ram,
            $mobile_offer->storage,
            $mobile_offer->battery_health,
            FeatureData::collect($mobile_offer->features),
            $mobile_offer->medially
        );
    }

    public static function contains_arabic(?string $text)
    {
        if (! $text || Str::length($text) === 0) {
            return false;
        }

        // Returns 1 if Arabic characters are found, 0 otherwise, or false on error
        return preg_match('/\p{Arabic}/u', $text[0]);
    }
}
