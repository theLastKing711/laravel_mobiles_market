<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MobileFeature extends Pivot
{
    /** @use HasFactory<\Database\Factories\ClassroomCourseTeacherFactory> */
    use HasFactory;

    public $incrementing = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<MobileOffer, $this>
     */
    public function mobileOffer(): BelongsTo
    {
        return $this->belongsTo(MobileOffer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<MobileOfferFeature, $this>
     */
    public function mobileOfferFeature(): BelongsTo
    {
        return $this->belongsTo(MobileOfferFeature::class);
    }
}
