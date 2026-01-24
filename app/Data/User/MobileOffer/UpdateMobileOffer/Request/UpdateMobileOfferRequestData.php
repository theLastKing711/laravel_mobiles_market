<?php

namespace App\Data\User\MobileOffer\UpdateMobileOffer\Request;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\MobileOffer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema()]
#[MergeValidationRules]
class UpdateMobileOfferRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public ?string $name_in_english,
        #[
            OAT\Property,
            GreaterThan(0)
        ]
        public ?int $price_in_usd,
        #[OAT\Property]
        public ?string $ram,
        #[OAT\Property]
        public ?string $storage,
        #[
            OAT\Property,
            Between(0, 100)
        ]
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

    public static function rules(?ValidationContext $context = null)
    {

        $mobile_offer_id =
            $context
                ->payload['id'];

        $mobile_offer_images =
            MobileOffer::query()
                ->with('medially')
                ->firstWhere(
                    'id',
                    $mobile_offer_id
                )
                ->medially;

        if ($mobile_offer_images->count() === 0) {
            /** @var array $temporary_uploaded_images_ids */
            $temporary_uploaded_images_ids =
                $context
                    ->payload['temporary_uploaded_images_ids'];

            if (count($temporary_uploaded_images_ids) == 0) {
                return [
                    'temporary_uploaded_images_ids' => [
                        'required',
                    ],
                ];
            }
        }

        return [];

    }
}
