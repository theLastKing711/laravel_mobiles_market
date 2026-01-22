<?php

namespace Tests\Feature\User\MyMobileOffer\Providers;

class GetMyMobileOffersProviderParameters
{
    public function __construct(
        public array $recrods,
        public string $search,
        public int $per_page,
        public int $expected_number_of_response_items
    ) {}
}
