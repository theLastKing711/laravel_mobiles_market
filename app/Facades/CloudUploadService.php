<?php

namespace App\Facades;

use App\Services\Api\cloudUploadService as APICloudUploadService;
use Illuminate\Support\Facades\Facade;

// required for laravel-ide-helper auto-complete
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
