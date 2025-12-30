<?php

namespace Database\Seeders;

use App\Models\MobileOfferFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Context;

class MobileOfferFeatureSeeder extends Seeder
{
    public const MOBILE_OFFER_FEATURES = [
        [
            'name' => 'مع الكرتونة',
        ],
        [
            'name' => 'مع الشاحن',
        ],
        [
            'name' => 'مع كامل الملحقات',
        ],
        [
            'name' => 'بحالة الوكالة',
        ],
        [
            'name' => 'بدون خدوش',
        ],
        [
            'name' => 'شحن لكافة المحافظات',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $mobile_offer_features =
            collect(
                MobileOfferFeatureSeeder::MOBILE_OFFER_FEATURES
            );

        MobileOfferFeature::query()
            ->insert(
                $mobile_offer_features
                    ->toArray()
            );

        Context::add(
            'mobile_offer_features',
            MobileOfferFeature::all()
        );

    }
}
