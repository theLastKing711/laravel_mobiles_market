<?php

namespace Tests\Feature\File;

use App\Http\Controllers\FileController;
use App\Models\TemporaryUploadedImages;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        FileController::class
    )
]
class DeleteTemporaryUploadedImageByPublicIdTest extends StoreTestCase
{
    use MediaServiceMocks;

    private function deleteTemporaryUploadedImageByPublicIdRequest(string $public_id)
    {
        return
           $this
               ->withRouteName(
                   route(
                       'files.temporary-uploaded-images.{public_id}.delete',
                       [
                           'public_id' => $public_id,
                       ]
                   )
               )
               ->deleteJsonData();
    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group('deleteTemporaryUploadedImageByPublicId'),
        Group('success'),
        Group('200')
    ]
    public function delete_temporary_uploaded_file_by_public_id_success_with_200_repsonse(): void
    {

        $temporary_uploaded_image =
            TemporaryUploadedImages::factory()
                ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->deleteTemporaryUploadedImageByPublicIdSuccess(
                $public_id
            );

        $response =
           $this
               ->deleteTemporaryUploadedImageByPublicIdRequest($public_id);

        $response->assertStatus(200);

    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group('deleteTemporaryUploadedImageByPublicId'),
        Group('error'),
        Group('500')
    ]
    public function delete_non_existing_temporary_uploaded_file_on_cloudinary_by_public_id_errors_with_500_repsonse(): void
    {

        $temporary_uploaded_image =
            TemporaryUploadedImages::factory()
                ->create();

        $public_id =
            $temporary_uploaded_image
                ->public_id;

        $this
            ->deleteTemporaryUploadedImageByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response
            =
            $this
                ->deleteTemporaryUploadedImageByPublicIdRequest(
                    $public_id
                );

        $response->assertStatus(500);

    }
}
