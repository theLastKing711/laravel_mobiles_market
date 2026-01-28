<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

// /** @property-read \Illuminate\Database\Eloquent\EloquentCollection<int, \App\Models\Media> $medially */
interface Mediable
{
    public function medially(): MorphMany;
}
