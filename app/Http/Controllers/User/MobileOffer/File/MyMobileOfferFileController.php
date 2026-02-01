<?php

namespace App\Http\Controllers\User\MobileOffer\File;

use App\Constants\SwaggerRoute;
use App\Constants\SwaggerTag;
use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Data\Shared\File\PathParameters\FilePublicIdPathParameterData;
use App\Data\Shared\File\PathParameters\MediaPublicIdPathParameterData;
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
use Tests\Feature\User\MyMobileOffer\File\MyMobileOfferFileTest;

class MyMobileOfferFileController extends MyMobileOfferFileControllerAbstract
{
    const TEST_FILE = MyMobileOfferFileTest::class;

    const MAIN_ROUTE = '/users/my-mobile-offers/files';

    #[OAT\Get(path: SwaggerRoute::MY_MOBILE_OFFER_FILE['cloudinary-presigned-urls'], tags: [SwaggerTag::MY_MOBILE_OFFER_FILE])]
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

    #[OAT\Post(path: SwaggerRoute::MY_MOBILE_OFFER_FILE['cloudinary-notifications-url'], tags: [SwaggerTag::MY_MOBILE_OFFER_FILE])]
    #[RequestBody(CloudinaryNotificationUrlRequestData::class)]
    #[SuccessNoContentResponse]
    public function saveTemporaryUploadedImageToDBOnCloudinaryUploadNotificationSuccess(CloudinaryNotificationUrlRequestData $request)
    {

        return
            MediaService::temporaryUploadMobileOfferImageFromCloudinaryNotification(
                $request
            );

    }

    #[OAT\Delete(path: SwaggerRoute::MY_MOBILE_OFFER_FILE['temporary-uploaded-images'], tags: [SwaggerTag::MY_MOBILE_OFFER_FILE])]
    #[SuccessNoContentResponse]
    public function deleteTemporaryUploadedImageByPublicId(FilePublicIdPathParameterData $deleteFileData)
    {
        MediaService::deleteTemporaryUploadedImageByPublicId(
            $deleteFileData
                ->public_id
        );

        return true;

    }

    #[OAT\Delete(path: SwaggerRoute::MY_MOBILE_OFFER_FILE['media'], tags: [SwaggerTag::MY_MOBILE_OFFER_FILE])]
    #[SuccessNoContentResponse]
    public function deleteMediaByPublicId(MediaPublicIdPathParameterData $request)
    {
        MediaService::deleteMediaByPublicId(
            $request
                ->public_id
        );

        return true;

    }
}
