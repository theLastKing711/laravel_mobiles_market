<?php

namespace Tests\Feature\File;

use App\Http\Controllers\FileController;
use App\Models\Media;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\StoreTestCase;
use Tests\TraitMocks\MediaServiceMocks;

#[
    Group(
        FileController::class
    )
]
class DeleteMediaByPublicIdTest extends StoreTestCase
{
    use MediaServiceMocks;

    private function deleteMediaByPublicIdRequest(string $public_id)
    {
        return
           $this
               ->withRouteName(
                   route(
                       'files.media.{public_id}.delete',
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
        Group('deleteMediaByPublicId'),
        Group('success'),
        Group('200'),
    ]
    public function delete_media_file_by_public_id_success_with_200_repsonse(): void
    {

        $media =
            Media::factory()
                ->forUserWithId($this->store->id)
                ->createOne();

        $public_id =
            $media
                ->public_id;

        $this->deleteMediaByPublicIdSuccess($public_id);

        $response
            =
            $this
                ->deleteMediaByPublicIdRequest($public_id);

        $response->assertStatus(200);

    }

    #[
        Test,
        Group('my-mobile-offers/files'),
        Group('deleteMediaByPublicId'),
        Group('error'),
        Group('500'),
    ]
    public function delete_non_existing_media_file_on_cloudinary_by_public_id_errors_with_500_repsonse(): void
    {

        $media =
            Media::factory()
                ->forUserWithId($this->store->id)
                ->createOne();

        $public_id =
            $media->public_id;

        $this
            ->deleteMediaByPublicIdThrowsFailedToDeleteImageException(
                $public_id
            );

        $response
            =
            $this
                ->deleteMediaByPublicIdRequest($public_id);

        $response->assertStatus(500);

    }
}
