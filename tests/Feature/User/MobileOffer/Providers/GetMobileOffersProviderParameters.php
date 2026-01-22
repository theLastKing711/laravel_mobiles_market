<?php

namespace Tests\Feature\User\MobileOffer\Providers;

class GetMobileOffersProviderParameters
{
    public function __construct(
        public array $recrods,
        public string $search,
        public int $per_page,
        public int $expected_number_of_response_items
    ) {}
}
