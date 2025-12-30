<?php

namespace App\Interfaces;

use App\Enum\FileUploadDirectory;
use App\Models\TemporaryUploadedImages;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IUploadable extends Mediable
{
    /**
     * Summary of temporaryUploadedImages
     *
     * @return MorphMany<TemporaryUploadedImages, $this>|MorphMany<TemporaryUploadedImages, \Eloquent>
     */
    public function temporaryUploadedImages(): MorphMany;

    /**
     * Summary of temporaryUploadedByCollectionName
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadedByCollectionName(FileUploadDirectory $file_upload_directory): Collection;
}
