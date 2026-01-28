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
    #[OAT\Get(path: '/users/my-mobile-offers/files/cloudinary-presigned-urls', tags: ['myMobileOfferFiles'])]
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

    #[OAT\Post(path: '/users/my-mobile-offers/files/cloudinary-notifications-url', tags: ['myMobileOfferFiles'])]
    #[RequestBody(CloudinaryNotificationUrlRequestData::class)]
    #[SuccessNoContentResponse]
    public function saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess(CloudinaryNotificationUrlRequestData $request)
    {

        return
            MediaService::temporaryUploadMobileOfferImageFromCloudinaryNotification(
                $request
            );

    }

    #[OAT\Delete(path: '/users/my-mobile-offers/files/{public_id}', tags: ['usersMyMobileOffersFiles'])]
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
