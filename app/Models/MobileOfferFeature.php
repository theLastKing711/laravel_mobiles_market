<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MobileOfferFeature extends Model
{
    /** @use HasFactory<\Database\Factories\MobileOfferFeatureFactory> */
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<MobileOffer, $this>
     */
    public function mobileOffers(): BelongsToMany
    {
        return $this->belongsToMany(MobileOffer::class, 'mobile_feature');
    }
}
