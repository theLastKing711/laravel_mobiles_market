<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFavouritesMobileOffer extends Pivot
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<MobileOffer, $this>
     */
    public function moblieOffer(): BelongsTo
    {
        return $this->belongsTo(MobileOffer::class, 'mobile_offer_id');
    }
}
