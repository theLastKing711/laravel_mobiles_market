<?php

namespace Tests\Feature\User\MyMobileOffer\Providers;

class CreateMyMobileOfferProviderParameters
{
    public function __construct(
        public string $name,
        public string $name_translation,
        public int $request_number_of_features,
        public int $request_number_of_images
    ) {}
}
