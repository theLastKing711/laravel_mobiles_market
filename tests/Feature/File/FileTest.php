<?php

namespace Tests\Feature\File;

use App\Enum\FileUploadDirectory;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\File\Abstractions\FileTestCase;
use Tests\TraitMocks\CloudUploadServiceMocks;

class FileTest extends FileTestCase
{
    use CloudUploadServiceMocks;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function get_cloudinary_presigned_url_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'cloudinary-presigned-url'
            );

        $response =
           $this
               ->getJsonData();

        $response->assertStatus(200);

    }

    #[Test]
    public function get_cloudinary_presigned_urls_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'cloudinary-presigned-urls'
            );

        $response =
           $this
               ->withQueryParameters([
                   'urls_count' => 2,
               ])
               ->getJsonData();

        $response->assertStatus(200);

    }

    #[Test]
    public function get_cloudinary_presigned_urls_with_duplicate_signatures_from_server_code_errors_with_500_response(): void
    {

        $urls_count = 3;

        $this->mockSignRequestsWithStaticReturn(FileUploadDirectory::MOBILE_OFFERS, $urls_count);

        $this
            ->withRoutePaths(
                'cloudinary-presigned-urls'
            );

        $response =
           $this
               ->withQueryParameters([
                   'urls_count' => $urls_count,
               ])
               ->getJsonData();

        $response->assertStatus(500);

    }

    #[Test]
    public function delete_file_by_public_id_success_with_200_repsonse(): void
    {

        $public_id = 'sample-public-id';

        $this
            ->mockDestroy(
                'sample-public-id'
                // str_replace(
                //     '-',
                //     '/',
                //     'sample-public-id'
                // )
            );

        $response =
           $this
               ->withRoutePaths(
                   'sample-public-id'
               )->deleteJsonData();

        $response->assertStatus(200);

    }
}
