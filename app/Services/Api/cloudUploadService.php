<?php

namespace App\Services\Api;

use App\Data\Shared\File\SignedRequestData;
use App\Enum\FileUploadDirectory;
use App\Exceptions\Api\Cloudinary\DuplicateSignedRequestSignature;
use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class cloudUploadService
{
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

    /**
     * @return SignedRequestData
     **/
    public function signRequest(FileUploadDirectory $directory, ?int $index = 0)
    {

        // to make sure signature is unique since paramsToSign are same in loop
        $timeStamp = time() + ($index * 10000);

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

        return new SignedRequestData(
            timestamp: $timeStamp,
            eager: $paramsToSign['eager'],
            folder: $directory,
            signature: $signature,
            api_key: config('cloudinary.api_key'),
            cloud_name: config('cloudinary.cloud_name'),
        );

    }

    public function signedUrlsHaveDuplicateSignature(Collection $signed_urls)
    {
        if ($signed_urls->unique('signature')->count() != $signed_urls->count()) {
            return true;
        }
    }

    /**
     * @throws DuplicateSignedRequestSignature
     **/
    public function signRequests(FileUploadDirectory $directory, int $count, ?int $index = 0)
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

        if ($presigned_uploads_data->unique(key: 'timestamp')->count() != $presigned_uploads_data->count()) {
            throw new DuplicateSignedRequestSignature;
        }
        if ($this->signedUrlsHaveDuplicateSignature($presigned_uploads_data)) {

            throw new DuplicateSignedRequestSignature;
        }

        return $presigned_uploads_data;
    }

    public function signTestRequests(int $count, ?int $index = 0)
    {

        $presigned_uploads_data = $this
            ->signRequests(
                FileUploadDirectory::TEST_FOLDER,
                $count
            );

    }

    public function destroy(string $public_id)
    {

        return Storage::delete($public_id);

    }

    // mobile-offer specific methods
    public function signMobileOffersRequest()
    {

        return $this
            ->signRequest(FileUploadDirectory::MOBILE_OFFERS);
    }

    /**
     * @throws DuplicateSignedRequestSignature
     **/
    public function signMobileOffersRequests(int $count, ?int $index = 0)
    {

        return $this
            ->signRequests(
                FileUploadDirectory::MOBILE_OFFERS,
                $count
            );
    }
}
