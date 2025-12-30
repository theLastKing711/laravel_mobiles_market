<?php

namespace App\Data\User\MobileOffer\GetFavouriteMobileOffers\Request;

use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
use OpenApi\Attributes as OAT;

#[Oat\Schema()]
class GetFavouriteMobileOffersRequestData extends PaginationQueryParameterData
{
    public function __construct(
        #[OAT\Property]
        public ?string $search,
        ?int $page,
        ?int $perPage,
    ) {
        parent::__construct($page, $perPage);
    }
}
