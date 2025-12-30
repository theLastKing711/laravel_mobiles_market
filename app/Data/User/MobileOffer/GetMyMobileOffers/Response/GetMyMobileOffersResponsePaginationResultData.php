<?php

namespace App\Data\User\MobileOffer\GetMyMobileOffers\Response;

use App\Data\Shared\Pagination\PaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;


#[TypeScript]
#[Oat\Schema(schema: 'UserMobileOfferGetMyMobileOffersResponseGetMyMobileOffersResponsePaginationResultData')]
class GetMyMobileOffersResponsePaginationResultData  extends PaginationResultData
{
    /** @param Collection<int, GetMyMobileOffersResponseData> $data */
    public function __construct(
        int $current_page,
        int $per_page,
        #[ArrayProperty(GetMyMobileOffersResponseData::class)]
        public Collection $data,
        int $total,
    ) {
        parent::__construct($current_page, $per_page, $total);
    }
}

