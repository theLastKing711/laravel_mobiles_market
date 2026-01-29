<?php

namespace App\Http\Controllers\User\MobileOffer\File;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Data\Shared\File\PathParameters\FilePublicIdPathParameterData;
use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Facades\CloudUploadService;
use App\Facades\MediaService;
use App\Http\Controllers\User\MobileOffer\File\Abstract\MyMobileOfferFileControllerAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\RequestBody;

class MyMobileOfferFileController extends MyMobileOfferFileControllerAbstract
{
    const MAIN_ROUTE = '/users/my-mobile-offers/files';

    const SWAGGER_TAG = 'myMobileOfferFiles';

    #[OAT\Get(path: self::MAIN_ROUTE.'/cloudinary-presigned-urls', tags: [self::SWAGGER_TAG])]
    #[QueryParameter('urls_count')]
    #[SuccessItemResponse('string', 'Fetched presigned upload successfully')]
    public function getCloudinaryPresignedUrls(Request $request): Collection
    {

        return
            CloudUploadService::signMobileOffersRequests(
                $request
                    ->urls_count
            );

    }

    #[OAT\Post(path: self::MAIN_ROUTE.'/cloudinary-notifications-url', tags: [self::SWAGGER_TAG])]
    #[RequestBody(CloudinaryNotificationUrlRequestData::class)]
    #[SuccessNoContentResponse]
    public function saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess(CloudinaryNotificationUrlRequestData $request)
    {

        return
            MediaService::temporaryUploadMobileOfferImageFromCloudinaryNotification(
                $request
            );

    }

    #[OAT\Delete(path: self::MAIN_ROUTE.'/{public_id}', tags: [self::SWAGGER_TAG])]
    #[SuccessNoContentResponse]
    public function delete(FilePublicIdPathParameterData $deleteFileData)
    {
        MediaService::deleteFileByPublicId(
            $deleteFileData
                ->public_id
        );

        return true;

    }
}
