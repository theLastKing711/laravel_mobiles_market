<?php

namespace App\Interfaces;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Enum\FileUploadDirectory;
use App\Models\TemporaryUploadedImages;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IUploadable extends Mediable
{
    /**
     * @return MorphMany<TemporaryUploadedImages, $this>|MorphMany<TemporaryUploadedImages, \Eloquent>
     */
    public function temporaryUploadedImages(): MorphMany;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadedImagesByFolder(FileUploadDirectory $file_upload_directory): Collection;

    public function temporaryUploadImageToFolderFromCloudinaryNotification(
        CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData,
        FileUploadDirectory $file_upload_directory
    ): TemporaryUploadedImages;
}
