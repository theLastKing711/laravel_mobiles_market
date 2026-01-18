<?php

namespace App\Services\Api;

use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class cloudUploadService
{
    // public function __construct(
    // #[Config('services.currencty_converter.api_key')]
    // protected string $api_key,
    // ) {}

    /**
     * Uploads an asset to a Cloudinary account.
     *
     * The asset can be:
     * * a local file path
     * * the actual data (byte array buffer)
     * * the Data URI (Base64 encoded), max ~60 MB (62,910,000 chars)
     * * the remote FTP, HTTP or HTTPS URL address of an existing file
     * * a private storage bucket (S3 or Google Storage) URL of a whitelisted bucket
     *
     * @param  string  $file  The asset to upload.
     * @param  array  $options  The optional parameters. See the upload API documentation.
     *
     * @throws ApiError
     */
    public function upload(string $file, array $options = []): array|\Cloudinary\Api\ApiResponse
    {

        return
         Cloudinary::upload($file, $options)
             ->getResponse();

    }

    public function destroy(string $public_id)
    {

        return Cloudinary::destroy($public_id);

    }

    // /**
    //  * Uploads an asset to a Cloudinary account.
    //  *
    //  * The asset can be:
    //  * * a local file path
    //  * * the actual data (byte array buffer)
    //  * * the Data URI (Base64 encoded), max ~60 MB (62,910,000 chars)
    //  * * the remote FTP, HTTP or HTTPS URL address of an existing file
    //  * * a private storage bucket (S3 or Google Storage) URL of a whitelisted bucket
    //  *
    //  * @param  Collection<string>  $files  The asset to upload.
    //  * @param  array  $options  The optional parameters. See the upload API documentation.
    //  *
    //  * @throws ApiError
    //  */
    // public function uploadMany(Collection $files, array $options = []): array|\Cloudinary\Api\ApiResponse
    // {

    //     $files_responses =
    //         $files
    //             ->map(function ($file) {
    //                 return
    //                     Cloudinary::upload($file, $options)
    //                         ->getResponse();
    //             });

    // }
}
