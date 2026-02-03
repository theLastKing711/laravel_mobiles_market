<?php

namespace Tests\Feature\File;

use App\Enum\FileUploadDirectory;
use App\Http\Controllers\FileController;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        FileController::class
    )
]
class GetCloudinaryPresignedUrlsTest extends StoreTestCase
{
    use MediaServiceMocks;

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group('getCloudinaryPresignedUrls'),
        Group('success'),
        Group('200')
    ]
    public function get_cloudinary_presigned_urls_success_with_200_response(): void
    {
        $urls_count = 2;

        $this
            ->mockSignRequestsWithUniqueSignRequestSignatures(
                FileUploadDirectory::TEST_FOLDER,
                $urls_count
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'files.cloudinary-presigned-urls',

                       [
                           'urls_count' => $urls_count,
                       ]
                   )
               )

               ->getJsonData();

        $response->assertStatus(200);

    }

    #[Test,
        Group('my-mobile-offers/files'),
        Group('getCloudinaryPresignedUrls'),
        Group('error'),
        Group('500')
    ]
    public function get_cloudinary_presigned_urls_with_duplicate_sign_request_signature_thrown_errors_with_500(): void
    {

        $urls_count = 3;

        $this
            ->mockSignRequestsThrowsDuplicateSignedRequestSignature(
                FileUploadDirectory::TEST_FOLDER,
                $urls_count
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'files.cloudinary-presigned-urls',
                       [
                           'urls_count' => $urls_count,
                       ]
                   )
               )
               ->getJsonData();

        $response->assertStatus(500);

    }
}
