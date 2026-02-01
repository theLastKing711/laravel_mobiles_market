<?php

namespace App\Http\Controllers;

use App\Constants\SwaggerRoute;
use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Data\Shared\File\PathParameters\FilePublicIdPathParameterData;
use App\Data\Shared\File\PathParameters\MediaPublicIdPathParameterData;
use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Enum\FileUploadDirectory;
use App\Facades\CloudUploadService;
use App\Facades\MediaService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\RequestBody;

#[
    OAT\PathItem(
        path: SwaggerRoute::FILE['media'],
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/filesMediaPublicIdPathParameterData',
            ),
        ],
    ),
]
#[
    OAT\PathItem(
        path: SwaggerRoute::FILE['temporary-uploaded-images'],
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/filesPublicIdPathParameterData',
            ),
        ],
    ),
]
class FileController extends Controller
{
    #[OAT\Get(path: SwaggerRoute::FILE['cloudinary-notifications-url'], tags: ['files'])]
    #[QueryParameter('urls_count')]
    #[SuccessItemResponse('string', 'Fetched presigned upload successfully')]
    public function getTestCloudinaryPresignedUrls(Request $request)
    {
        return CloudUploadService::signRequests(
            FileUploadDirectory::TEST_FOLDER,
            $request->urls_count
        );

    }

    #[OAT\Post(path: SwaggerRoute::FILE['cloudinary-notifications-url'], tags: ['files'])]
    #[RequestBody(CloudinaryNotificationUrlRequestData::class)]
    #[SuccessNoContentResponse]
    public function saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess(CloudinaryNotificationUrlRequestData $request)
    {

        MediaService::createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification(
            $request,
            FileUploadDirectory::TEST_FOLDER
        );

    }

    #[OAT\Delete(path: SwaggerRoute::FILE['media'], tags: ['files'])]
    #[SuccessNoContentResponse]
    public function deleteMediaByPublicId(MediaPublicIdPathParameterData $request)
    {
        MediaService::deleteMediaByPublicId(
            $request
                ->public_id
        );

        return true;

    }

    #[OAT\Delete(path: SwaggerRoute::FILE['temporary-uploaded-images'], tags: ['files'])]
    #[SuccessNoContentResponse]
    public function deleteTemporaryUploadedImageByPublicId(FilePublicIdPathParameterData $request)
    {
        MediaService::deleteTemporaryUploadedImageByPublicId(
            $request
                ->public_id
        );

        return true;

    }
}
