<?php

namespace Database\Seeders;

use App\Enum\Language;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Context;

class MobileOfferSeeder extends Seeder
{
    public const MOBILE_OFFERS = [
        [
            'name_in_english' => 'iPhone XR',
            'name_in_arabic' => 'أيفون إكس أر',
            'mobile_name_language_when_uploaded' => 2,
            'price_in_usd' => 300,
            'is_sold' => false,
            'screen_size' => '6.1',
            'screen_type' => 'IPS',
            'cpu' => 'A12 Bionic chip',
            'ram' => '3GB',
            'storage' => '256GB',
            'battery_size' => 2900,
            'battery_health' => 90,
            'number_of_sims' => 1,
            'number_of_esims' => 0,
            'color' => 'blue',
        ],
        [
            'name_in_english' => 'iPhone 11',
            'name_in_arabic' => 'أيفون 11',
            'mobile_name_language_when_uploaded' => 2,
            'price_in_usd' => 400,
            'is_sold' => false,
            'screen_size' => '6.1',
            'screen_type' => 'IPS',
            'cpu' => 'A13 Bionic chip',
            'ram' => '4GB',
            'storage' => '256GB',
            'battery_size' => 3110,
            'battery_health' => 85,
            'number_of_sims' => 1,
            'number_of_esims' => 0,
            'color' => 'blue',
        ],
        // [
        //     'name_in_english' => 'Samsung Galaxy A06',
        //     'name_in_arabic' => 'سامسونغ غالاكسي A06',
        //     'mobile_name_language_when_uploaded' => Language::EN,
        //     'price_in_usd' => 75,
        //     'is_sold' => false,
        //     'screen_size' => '6.7',
        //     'screen_type' => 'IPS',
        //     'cpu' => 'MediaTek Helio G85',
        //     'ram' => '4GB',
        //     'storage' => '128GB',
        //     'battery_size' => 5000,
        //     'battery_health' => 95,
        //     'number_of_sims' => 2,
        //     'number_of_esims' => 0,
        //     'color' => 'yellow',
        // ],
        // [
        //     'name_in_english' => 'Samsung Galaxy A06',
        //     'name_in_arabic' => 'سامسونغ غالاكسي A06',
        //     'mobile_name_language_when_uploaded' => Language::EN,
        //     'price_in_usd' => 100,
        //     'is_sold' => false,
        //     'screen_size' => '6.7',
        //     'screen_type' => 'IPS',
        //     'cpu' => 'MediaTek Helio G85',
        //     'ram' => '4GB',
        //     'storage' => '256GB',
        //     'battery_size' => 5000,
        //     'battery_health' => 95,
        //     'number_of_sims' => 2,
        //     'number_of_esims' => 0,
        //     'color' => 'yellow',
        // ],
        // [
        //     'name_in_english' => 'Huawei Mate X7',
        //     'name_in_arabic' => 'هواوي مات X7',
        //     'mobile_name_language_when_uploaded' => Language::EN,
        //     'price_in_usd' => 1600,
        //     'is_sold' => false,
        //     'screen_size' => '7.4',
        //     'screen_type' => 'OLED',
        //     'cpu' => 'MediaTek Helio G85',
        //     'ram' => '12GB',
        //     'storage' => '256GB',
        //     'battery_size' => 5525,
        //     'battery_health' => 92,
        //     'number_of_sims' => 2,
        //     'number_of_esims' => 2,
        //     'color' => 'yellow',
        // ],
        // [
        //     'name_in_english' => 'Honor 500 Pro',
        //     'name_in_arabic' => 'أونور 500 برو',
        //     'mobile_name_language_when_uploaded' => Language::EN,
        //     'price_in_usd' => 440,
        //     'is_sold' => false,
        //     'screen_size' => '6.55',
        //     'screen_type' => 'OLED',
        //     'cpu' => 'Qualcomm SM8750-AB Snapdragon 8 Elite',
        //     'ram' => '12GB',
        //     'storage' => '1TB',
        //     'battery_size' => 8000,
        //     'battery_health' => 100,
        //     'number_of_sims' => 2,
        //     'number_of_esims' => 2,
        //     'color' => 'pink',
        // ],

    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $mobile_offers =
            collect(
                MobileOfferSeeder::MOBILE_OFFERS
            );

        $store =
           User::query()
               ->user()
               ->first();

        $store
            ->mobileOffers()
            ->createMany(
                MobileOfferSeeder::MOBILE_OFFERS
            );

        Context::add(
            'mobile_offers',
            $store->mobileOffers
        );

    }
}
