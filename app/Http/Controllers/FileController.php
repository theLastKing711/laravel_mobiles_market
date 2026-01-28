<?php

namespace App\Http\Controllers;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Data\Shared\File\PathParameters\FilePublicIdPathParameterData;
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
        path: '/files/{public_id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/filesPublicIdPathParameterData',
            ),
        ],
    ),
]
class FileController extends Controller
{
    #[OAT\Get(path: '/files/cloudinary-presigned-urls', tags: ['files'])]
    #[QueryParameter('urls_count')]
    #[QueryParameter('resource')]
    #[SuccessItemResponse('string', 'Fetched presigned upload successfully')]
    public function getTestCloudinaryPresignedUrls(Request $request)
    {
        return CloudUploadService::signRequests(
            FileUploadDirectory::TEST_FOLDER,
            $request->urls_count
        );

    }

    #[OAT\Post(path: '/files/cloudinary-notifications-url', tags: ['files'])]
    #[RequestBody(CloudinaryNotificationUrlRequestData::class)]
    #[SuccessNoContentResponse]
    public function saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess(CloudinaryNotificationUrlRequestData $request)
    {

        MediaService::createTemporaryUploadedImageFromCloudinaryUploadSuccessNotification(
            $request,
            FileUploadDirectory::TEST_FOLDER
        );

    }

    #[OAT\Delete(path: '/files/{public_id}', tags: ['files'])]
    #[SuccessNoContentResponse]
    public function delete(FilePublicIdPathParameterData $request)
    {
        MediaService::deleteFileByPublicId(
            $request
                ->public_id
        );

        return true;

    }
}
