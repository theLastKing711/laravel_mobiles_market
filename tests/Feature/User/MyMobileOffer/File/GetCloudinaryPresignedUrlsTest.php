<?php

namespace Tests\Feature\User\MyMobileOffer\File;

use App\Enum\FileUploadDirectory;
use App\Http\Controllers\User\MobileOffer\File\MyMobileOfferFileController;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        MyMobileOfferFileController::class
    )
]
class GetCloudinaryPresignedUrlsTest extends StoreTestCase
{
    use MediaServiceMocks;

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group(MyMobileOfferFileController::class),
        Group('getCloudinaryPresignedUrls'),
        Group('success'),
        Group('200')
    ]
    public function get_cloudinary_presigned_urls_success_with_200_response(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsWithUniqueSignRequestSignatures(
                FileUploadDirectory::MOBILE_OFFERS,
                $urls_count
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.my-mobile-offers.files.cloudinary-presigned-urls',
                       [
                           'urls_count' => $urls_count,
                       ]
                   )
               )
               ->getJsonData();

        $response->assertStatus(200);

    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group(MyMobileOfferFileController::class),
        Group('getCloudinaryPresignedUrls'),
        Group('error'),
        Group('500')

    ]
    public function get_cloudinary_presigned_urls_with_duplicate_sign_request_signature_thrown_errors_with_500(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsThrowsDuplicateSignedRequestSignature(
                FileUploadDirectory::MOBILE_OFFERS,
                $urls_count
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.my-mobile-offers.files.cloudinary-presigned-urls',
                       [
                           'urls_count' => $urls_count,
                       ]
                   )
               )
               ->getJsonData();

        $response->assertStatus(500);

    }
}
