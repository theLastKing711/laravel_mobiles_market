<?php

namespace App\Facades;

use App\Services\API\cloudUploadService as APICloudUploadService;
use Illuminate\Support\Facades\Facade;

// required for laravel-ide-helper auto-complete
/**
 * @mixin \App\Services\mediaService
 */
class CloudUploadService extends Facade
{
    /**
     * @mixin APICloudUploadService
     */
    public static function getFacadeAccessor(): string
    {
        return APICloudUploadService::class;
    }
}
