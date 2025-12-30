<?php

namespace App\Http\Controllers;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Data\Shared\File\PathParameters\FilePublicIdPathParameterData;
use App\Data\Shared\File\UploadFileData;
use App\Data\Shared\File\UploadFileListData;
use App\Data\Shared\File\UploadFileResponseData;
use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Request\FormDataRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Enum\FileUploadDirectory;
use App\Models\Media;
use App\Models\TemporaryUploadedImages;
use App\Models\User;
use Cloudinary\Api\ApiUtils;
use Cloudinary\Asset\Image;
use Cloudinary\Configuration\CloudConfig;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\RequestBody;

#[
    OAT\PathItem(
        path: '/files/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/filesPublicIdPathParameterData',
            ),
        ],
    ),
]
class FileController extends Controller
{
    #[OAT\Get(path: '/admin/files', tags: ['files'])]
    #[SuccessNoContentResponse('File uploaded successfully')]
    public function index()
    {
        return [];
    }

    #[OAT\Post(path: '/files', tags: ['files'])]
    #[FormDataRequestBody(UploadFileData::class)]
    #[SuccessItemResponse(UploadFileResponseData::class, 'File uploaded successfully')]
    public function store(UploadFileData $uploadFileData, Request $request)
    {
        $file = $uploadFileData->file;

        Log::info(
            'accessing FileController store, with files {files}',
            ['files' => $file]
        );
        //        abort(404);
        $file_path = $file->getRealPath();

        Log::info('file path {file_path}', ['file_path' => $file_path]);

        // https://cloudinary.com/documentation/image_upload_api_reference#upload_method
        // https://cloudinary.com/documentation/image_upload_api_reference
        // https://cloudinary.com/documentation/transformation_reference
        // https://cloudinary.com/documentation/image_optimization
        // https://cloudinary.com/documentation/eager_and_incoming_transformations#eager_transformations
        // /eager which apply multiple transformations on the fly during upload
        // and return multiple transformed images, as opposed the transformation
        // which works only on the main image and return it
        // alternative to eager is eager_async,
        // which apply transformation after upload request is done
        // when first visited by user i.e end-user using front end
        // https://cloudinary.com/documentation/transformation_reference
        // if width => value used with quality => auto will be the same as width => value alone
        // 'effect' => ['bgremoval|make_transparent'] to remove background  is bad and doesn't work
        $result = Cloudinary::upload($file_path, [
            'eager' => [ // list of transformation objects -> https://cloudinary.com/documentation/transformation_reference
                [
                    'width' => 400,
                    // 'height' => 500,
                    // 'crop' => 'pad',
                    // 'pad',
                    // 'scale' => 'auto',
                ],
            ],
            // 'eager' => [ //list of transformation objects -> https://cloudinary.com/documentation/transformation_reference
            //     //here we upload and get x images for the provided file_path in response where x is number of objects in below array
            //     // [
            //     //     'width' => 90,
            //     // ],
            //     [
            //         'width' => 700,
            //         'height' => 700,
            //         'crop' => 'pad',
            //         // 'pad',
            //         // 'scale' => 'auto',
            //     ],
            //     // [
            //     //     'width' => 700,
            //     //     // 'pad',
            //     //     // 'scale' => 'auto',
            //     // ],
            //     //             "width": 700,
            //     //   "height": 1941,
            //     //   "bytes": 148109,
            //     // 'transformation' => [ // transforms the uploaded image and return it with transformations applied to it in response
            //     //     // 'quality' => 'auto',
            //     // 'width' => 90,
            //     //     'effect' => [
            //     //         'make_transparent',
            //     //         // 'bgremoval',
            //     // ],
            //     //     // 'height' => 90,
            //     // ],
            // ],
            // 'transformation' => [
            //     'width' => 700,
            //     'quality' => 'auto',
            // ],
        ]);

        // get request response object
        $response = $result->getResponse();

        return $response;

        return new UploadFileResponseData(
            url: $eager_file['secure_url'],
            public_id: $eager_file['secure_url'],
        );

        // the url of the uploaded image -> real image on usage
        // example result: https:cloudinary$pathToImage
        $cloudinary_image_path = $result->getSecurePath();

        // unique identifer for the image can
        // can be used to get the image cloudinary resource
        // example result: jasldkjGAsdfkj
        $cloudinary_public_id = $result->getPublicId();

        Log::info($cloudinary_image_path);

        return new UploadFileResponseData(
            url: $cloudinary_image_path,
            public_id: $cloudinary_public_id,
        );
    }

    #[OAT\Post(path: '/files/many', tags: ['files'])]
    #[FormDataRequestBody(UploadFileListData::class)]
    #[SuccessListResponse(UploadFileListData::class, 'Files uploaded successfully')]
    public function storeMany(UploadFileListData $uploadFileData, Request $request)
    {
        Log::info($uploadFileData);

        $files = $uploadFileData->files;

        // /** @var Collection<int, Media> $uploaded_medias */
        // $session_uploaded_media = collect([]);

        /** @var Collection<int, TemporaryUploadedImages> $uploaded_medias */
        $temporary_uploaded_images = collect([]);

        /** @var User $var description */
        $logged_user = Auth::User()->loadCount('temporaryUploadedImages');

        /** @var Collection<int, Media> $uploaded_medias */
        $uploaded_medias_data = $files->map(
            function ($file) use ($temporary_uploaded_images, $logged_user) {

                Log::info(
                    'accessing FileController storeMany, with files {files}',
                    ['files' => $file]
                );

                $file_path = $file->getRealPath();

                Log::info('file path {file_path}', ['file_path' => $file_path]);

                // https://cloudinary.com/documentation/image_upload_api_reference
                // https://cloudinary.com/documentation/transformation_reference
                // https://cloudinary.com/documentation/image_optimization
                // https://cloudinary.com/documentation/eager_and_incoming_transformations#eager_transformations
                // /eager which apply multiple transformations on the fly during upload
                // and return multiple transformed images, as opposed the transformation
                // which works only on the main image and return it
                // alternative to eager is eager_async,
                // which apply transformation after upload request is done
                // when first visited by user i.e end-user using front end
                // https://cloudinary.com/documentation/transformation_reference
                // if width => value used with quality => auto will be the same as width => value alone
                // 'effect' => ['bgremoval|make_transparent'] to remove background  is bad and doesn't work
                // return base image plus two images one with transformation of width 90 and auto height
                // and second is width 700 and height is 700 plus cropping
                $response = Cloudinary::upload($file_path, [
                    'eager' => [ // list of transformation objects -> https://cloudinary.com/documentation/transformation_reference
                        [
                            'width' => 500,
                            // 'height' => 500,
                            'crop' => 'pad',
                        ],
                        // [
                        //     'width' => 700,
                        // ],

                    ],
                ]);

                // get request response object
                // return $result->getResponse();
                // the url of the uploaded image -> real image on usage
                // example result: https:cloudinary$pathToImage
                // $cloudinary_image_path = $response->getSecurePath();

                // unique identifer for the image
                // can be used to get the image cloudinary resource
                // example result: jasldkjGAsdfkj
                // $cloudinary_public_id = $response->getPublicId();

                // $uploaded_media = Media::fromCloudinaryUploadResponse($response);

                $uploaded_media = TemporaryUploadedImages::fromCloudinaryUploadResponse($response, $logged_user->id);

                $temporary_uploaded_images->push($uploaded_media);

                // $media = new Media;
                // $media->file_name = $response_file->getFileName();
                // // $media->file_url = $response_file->getSecurePath();
                // // $media->file_url = $response_file->getSecurePath();
                // $media->file_url = $first_eager_response['secure_url'];
                // // $media->size = $first_eager_response->getSize();
                // $media->size = $first_eager_response['bytes'];
                // $media->file_type = $response_file->getFileType();
                //         $session_uploaded_media->push($uploaded_media);

                return new UploadFileResponseData(
                    url: $uploaded_media->file_url,
                    public_id: $response->getPublicId()
                );
            }
        );

        $user_has_previous_temporary_uploaded_images =
            $logged_user->temporary_uploaded_images_count != 0;

        if ($user_has_previous_temporary_uploaded_images) {

            $logged_user
                ->temporaryUploadedImages()
                ->delete();
        }

        $logged_user
            ->temporaryUploadedImages()
            ->saveMany($temporary_uploaded_images);

        // $upload_cars_images_session_key = config('constants.session.upload_car_images');

        // $request
        //     ->session()
        //     ->put(
        //         $upload_cars_images_session_key,
        //         $session_uploaded_media
        //     );

        return $uploaded_medias_data;

    }

    // eager upload with 2 transformation response
    // {
    //     "asset_id": "e993e6a01e81b12c360e4ce2757cd9b9",
    //     "public_id": "sg8oi7r0xr3cknbddbe7",
    //     "version": 1729950437,
    //     "version_id": "e2d0f7ad394cf1c2bd0aeb4a116cc7cb",
    //     "signature": "84d708aec3b07af6444a12453dfa06c723a050c1",
    //     "width": 851,
    //     "height": 2360,
    //     "format": "jpg",
    //     "resource_type": "image",
    //     "created_at": "2024-10-26T13:47:17Z",
    //     "tags": [],
    //     "bytes": 427811,
    //     "type": "upload",
    //     "etag": "ace3b53cdb09459b36e05a621b765b02",
    //     "placeholder": false,
    //     "url": "http://res.cloudinary.com/dkmsfsa7c/image/upload/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
    //     "secure_url": "https://res.cloudinary.com/dkmsfsa7c/image/upload/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
    //     "folder": "",
    //     "original_filename": "phpZnCtHW",
    //     "eager": [
    //       {
    //         "transformation": "w_90",
    //         "width": 90,
    //         "height": 250,
    //         "bytes": 5323,
    //         "format": "jpg",
    //         "url": "http://res.cloudinary.com/dkmsfsa7c/image/upload/w_90/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
    //         "secure_url": "https://res.cloudinary.com/dkmsfsa7c/image/upload/w_90/v1729950437/sg8oi7r0xr3cknbddbe7.jpg"
    //       },
    //       {
    //         "transformation": "w_700",
    //         "width": 700,
    //         "height": 1941,
    //         "bytes": 148109,
    //         "format": "jpg",
    //         "url": "http://res.cloudinary.com/dkmsfsa7c/image/upload/w_700/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
    //         "secure_url": "https://res.cloudinary.com/dkmsfsa7c/image/upload/w_700/v1729950437/sg8oi7r0xr3cknbddbe7.jpg"
    //       }
    //     ],
    //     "api_key": "379721987165773"
    //   }

    #[OAT\Delete(path: '/files/{public_id}', tags: ['files'])]
    #[SuccessNoContentResponse]
    public function delete(FilePublicIdPathParameterData $deleteFileData, Request $request)
    {

        Log::info('file public id  {data}', ['data' => $deleteFileData->public_id]);

        $public_id =
              str_replace(
                  '-',
                  '/',
                  $deleteFileData->public_id,
              );

        // if image is created before parent model is created (i.e on create page)
        TemporaryUploadedImages::query()
            ->firstWhere(
                'public_id',
                $public_id
            )
            ?->delete();

        // if image is created after parent model is created(i.e on update page)
        Media::firstWhere(
            'public_id',
            $public_id
        )
            ?->delete();

        // $uploaded_car_images_session_key = config('constants.session.upload_car_images');
        // /** @var Collection<int, Media> $user_car_medias */
        // $user_car_medias =
        //    $request
        //        ->session()
        //        ->get($uploaded_car_images_session_key);

        // if ($user_car_medias) {

        //     Log::info('session data {session_data}', ['session_data' => $user_car_medias]);

        // $updated_user_car_medias =
        //     $user_car_medias
        //         ->filter(function ($item) use ($deleteFileData) {
        //             return $item->file_name != $deleteFileData->public_id;
        //         });

        // $request->session()->forget($uploaded_car_images_session_key);

        // }

        Cloudinary::destroy($deleteFileData->public_id);

        return true;
    }

    #[OAT\Get(path: '/files/cloudinary-presigned-url', tags: ['files'])]
    #[SuccessItemResponse('string', 'Fetched presigned upload successfully')]
    public function getCloudinaryPresignedUrl()
    {

        $paramsToSign = [
            'timeStamp' => time(),
            'folder' => 'mobiles_market',
        ];

        // https://cloudinary.com/documentation/authentication_signatures
        ApiUtils::signRequest(
            $paramsToSign,
            new CloudConfig()
                ->setCloudConfig(
                    CloudConfig::API_SECRET,
                    config('cloudinary.api_secret')
                )
        );

        return [
            ...$paramsToSign,
            'api_key' => config('cloudinary.api_key'),
            'folder' => 'mobiles_market',
            'cloud_name' => config('cloudinary.cloud_name'),
        ];

        return $signed_url;

    }

    #[OAT\Get(path: '/files/cloudinary-presigned-urls', tags: ['files'])]
    #[QueryParameter('urls_count')]
    #[QueryParameter('resource')]
    #[SuccessItemResponse('string', 'Fetched presigned upload successfully')]
    public function getCloudinaryPresignedUrls(Request $request)
    {

        $urls_list =
            Collection::times($request->urls_count, fn ($number) => $number); // [1, 2, 3, 4, 5]

        $presigned_uploads_data =
            $urls_list
                ->map(
                    function ($item, $index) {

                        $timeStamp = time() + ($index * 10000);

                        // $timeStamp = time();

                        // to make sure signature is unique since paramsToSign are same in loop
                        // $timeStamp = time() + $index;
                        // sleep(0.1);

                        $paramsToSign = [
                            'timestamp' => $timeStamp,
                            // 'public_id' => 'sample_image',
                            'eager' => 't_thumbnail|t_main',
                            'folder' => FileUploadDirectory::MOBILE_OFFERS->value,
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
                );

        return $presigned_uploads_data;

    }

    #[OAT\Post(path: '/files/mobile-offer-cloudinary-notifications-url', tags: ['files'])]
    #[RequestBody(CloudinaryNotificationUrlRequestData::class)]
    #[SuccessNoContentResponse]
    public function mobileOfferCloudinaryNotificationUrl(CloudinaryNotificationUrlRequestData $request)
    {

        return
           Auth::User()
               ->temporaryUploadMobileOfferImageFromCloudinaryNotification(
                   $request
               );

    }
}
