<?php

namespace Tests\Feature\Shared;

use App\Enum\FileUploadDirectory;
use App\Exceptions\Api\Cloudinary\DuplicateSignedRequestSignature;
use App\Facades\CloudUploadService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\TraitMocks\CloudUploadServiceMocks;
use Tests\Traits\AdminTrait;

class CloudUploadServiceTest extends TestCase
{
    use AdminTrait, CloudUploadServiceMocks, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->seed(
                [
                    RolesAndPermissionsSeeder::class,
                ]
            );

    }

    #[Test, group('signedUrlsHaveDuplicateSignature')]
    public function signed_urls_have_duplicate_signature_when_collection_has_unique_signatures_returns_false(): void
    {

        $signed_requests =
            collect([
                ['signature' => 'abcd'],
                ['signature' => 'asd'],
                ['signature' => 'absd'],
                ['signature' => 'abcde'],
            ]);

        $this
            ->assertEquals(
                false,
                CloudUploadService::signedUrlsHaveDuplicateSignature($signed_requests)
            );

    }

    #[Test, group('signedUrlsHaveDuplicateSignature')]
    public function signed_urls_have_duplicate_signature_when_collection_has_duploicate_signature_returns_true(): void
    {

        $signed_requests =
            collect([
                ['signature' => 'abcde'],
                ['signature' => 'asd'],
                ['signature' => 'absd'],
                ['signature' => 'abcde'],
            ]);

        $this
            ->assertEquals(
                true,
                CloudUploadService::signedUrlsHaveDuplicateSignature($signed_requests)
            );

    }

    #[Test, group('signRequest')]
    public function cloud_upload_service_sign_request_for_test_folder_success(): void
    {

        $signed_request =
            CloudUploadService::signRequest(
                FileUploadDirectory::TEST_FOLDER
            );

        $this
            ->assertEquals(
                FileUploadDirectory::TEST_FOLDER,
                $signed_request->folder,
            );

    }

    #[Test, group('signRequests')]
    public function cloud_upload_service_signs_3_requests_with_unique_signatures_success(): void
    {

        $number_of_signed_requests = 3;

        $this->mockSignRequestWithUniqueSignatueNTimes(
            FileUploadDirectory::TEST_FOLDER,
            3
        );

        $items = CloudUploadService::signRequests(
            FileUploadDirectory::TEST_FOLDER,
            $number_of_signed_requests,
        );

    }

    #[Test, group('signRequests')]
    public function cloud_upload_service_signs_3_requests_with_static_signatures_throws_duplicate_signed_request_signature(): void
    {

        $number_of_signed_requests = 3;

        $this->mockSignRequestWithStaticSignatueNTimes(
            FileUploadDirectory::TEST_FOLDER,
            3
        );

        $this
            ->expectException(
                DuplicateSignedRequestSignature::class
            );

        CloudUploadService::signRequests(
            FileUploadDirectory::TEST_FOLDER,
            $number_of_signed_requests,
        );

    }
}
