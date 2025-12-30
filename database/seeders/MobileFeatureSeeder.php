<?php

namespace Database\Seeders;

use App\Models\MobileOffer;
use App\Models\MobileOfferFeature;
use Illuminate\Container\Attributes\Context;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class MobileFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Collection<MobileOffer>  $mobile_offers
     * @param  Collection<MobileOfferFeature>  $mobile_offer_features
     */
    public function run(#[Context('mobile_offers')] Collection $mobile_offers, #[Context('mobile_offer_features')] Collection $mobile_offer_features): void
    {

        $mobile_offers
            ->each(function (MobileOffer $mobile_offer) use ($mobile_offer_features) {

                $random_mobile_offer_features_ids =
                    fake()
                        ->randomElements(
                            $mobile_offer_features
                                ->pluck('id'),
                            3
                        );

                $mobile_offer
                    ->features()
                    ->attach(
                        $random_mobile_offer_features_ids
                    );

            });
    }
}
