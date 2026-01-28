<?php

namespace App\Trait;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * MediaAlly
 *
 * Provides functionality for attaching Cloudinary files to an eloquent model.
 * Whether the model should automatically reload its media relationship after modification.
 */
trait MediaAlly
{
    /**
     * Relationship for all attached media.
     */
    public function medially(): MorphMany
    {
        return $this->morphMany(Media::class, 'medially');
    }
}
