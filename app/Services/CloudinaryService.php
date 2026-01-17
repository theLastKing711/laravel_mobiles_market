<?php

namespace App\Services;

use App\Enum\FileUploadDirectory;
use Cloudinary\Api\ApiUtils;
use Illuminate\Support\Collection;

class CloudinaryService
{
    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     */
    public function signRequests(int $count, FileUploadDirectory $directory, ?int $index = 0)
    {

        $urls_list =
            Collection::times($count, fn ($number) => $number); // [1, 2, 3, 4, 5]

        $presigned_uploads_data =
            $urls_list
                ->map(
                    function ($item, $index) use ($directory) {

                        return $this->signRequest(
                            $directory,
                            $index
                        );
                    }
                );

        return $presigned_uploads_data;
    }

    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     */
    public function signRequest(FileUploadDirectory $directory, ?int $index = 0)
    {

        $timeStamp = time() + ($index * 10000);

        // $timeStamp = time();

        // to make sure signature is unique since paramsToSign are same in loop
        // $timeStamp = time() + $index;
        // sleep(0.1);

        $paramsToSign = [
            'timestamp' => $timeStamp,
            // 'public_id' => 'sample_image',
            'eager' => 't_thumbnail|t_main',
            'folder' => $directory->value,
            // 'tags' => FileUploadDirectory::MOBILE_OFFERS->value,
            // 'context' => "resourse={$request->resource}",
            // 'context' => 'caption=My new image|author=John Doe',
            // 'eager_async' => true,
        ];

        // https://cloudinary.com/documentation/authentication_signatures
        $signature = ApiUtils::signParameters(
            $paramsToSign,
            config('cloudinary.api_secret')
        );

        return [
            ...$paramsToSign,
            'signature' => $signature,
            'api_key' => config('cloudinary.api_key'),
            'cloud_name' => config('cloudinary.cloud_name'),
        ];

    }
}
