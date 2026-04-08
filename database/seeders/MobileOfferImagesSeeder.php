<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\MobileOffer;
use Database\Factories\MediaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class mobileOfferImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobile_offers =
            MobileOffer
                ::query()
                ->all();


        $mobile_offers
            ->each(
                function (MobileOffer $mobile_offer) {

                    $image = Media
                        ::factory()
                        ->withCollectionName(\App\Enum\FileUploadDirectory::MOBILE_OFFERS)
                        ->withImageUrl()
                        ->forMobileOfferWithId($mobile_offer->id)
                        ->create();
                }
            );
    }
}
