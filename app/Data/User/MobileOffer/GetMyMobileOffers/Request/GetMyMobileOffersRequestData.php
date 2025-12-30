<?php

namespace App\Data\User\MobileOffer\GetMyMobileOffers\Request;

use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
use OpenApi\Attributes as OAT;

#[Oat\Schema()]
class GetMyMobileOffersRequestData extends PaginationQueryParameterData
{
    public function __construct(
        ?int $page,
        ?int $perPage,

        #[OAT\Property]
        public ?string $search,
    ) {
        parent::__construct($page, $perPage);
    }
}
