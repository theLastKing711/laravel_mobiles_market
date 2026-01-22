<?php

namespace Tests\Feature\User\MyMobileOffer\Providers;

class UpdateMyMobileOfferProviderParameters
{
    public function __construct(
        public int $mobile_offer_old_number_of_features,
        public int $mobile_offer_old_number_of_images,
        public string $name,
        public string $name_translation,
        public int $request_number_of_features,
        public int $request_number_of_images
    ) {}
}
