<?php

namespace App\Services;

use App\Data\Shared\File\CloudinaryNotificationUrlRequestData;
use App\Data\Shared\File\UpdateFileData;
use App\Data\Shared\Media\ModelAndMediable;
use App\Enum\FileUploadDirectory;
use App\Exceptions\Api\Cloudinary\FailedToDeleteImageException;
use App\Facades\CloudUploadService;
use App\Models\Media;
use App\Models\TemporaryUploadedImages;
use Cloudinary;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\HttpStatusCode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class mediaService
{
    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     *
     * @param  UpdateFileData[]|Collection<UpdateFileData>  $request_files
     * @return void
     *
     * @throws ApiError
     */
    public function updateMediaForModel(ModelAndMediable $model, array|Collection $request_files)
    {
        /** @var Collection<int, string> $file_to_update_ids */
        $file_to_update_ids = $request_files->pluck('uid');

        /** @var Collection<int, Media> $medias_to_delete */
        $medias_to_delete =
            $model
                ->medially
                ->whereNotIn('id', $file_to_update_ids);

        Log::info('media ids to delete {ids}', ['ids' => $medias_to_delete]);

        $model->detachMedia($medias_to_delete);

        /** @var Collection<int, string> $existing_media_ids */
        $existing_media_ids =
            $model
                ->medially
                ->pluck('id');

        /** @var Collection<int, string> $media_to_add_urls */
        $media_to_add_urls = $request_files->filter(
            fn ($file) => ! $existing_media_ids->contains($file->uid)
        )->pluck('url');

        foreach ($media_to_add_urls as &$media_url) {
            $model->attachRemoteMedia($media_url);
        }
    }

    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     *
     * @param  int[]|Collection<int, int>  $public_ids
     * @return void
     *
     * @throws ApiError
     */
    public function createMediaForModel(ModelAndMediable $model, array|Collection $public_ids)
    {

        foreach ($public_ids as &$public_id) {

            $cloud_image = Cloudinary::getImage($public_id);

            $cloud_image_url = $cloud_image->toUrl();

            $model->attachRemoteMedia($cloud_image_url);
        }
    }

    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     */
    public function removeAssociatedMediaForModel(ModelAndMediable $model): void
    {
        FacadesLog::info('hello world');
        $model->detachMedia();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, TemporaryUploadedImages>
     */
    public function temporaryUploadImageToFolderFromCloudinaryNotification(
        CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData,
        FileUploadDirectory $file_upload_directory
    ): TemporaryUploadedImages {

        return
            Auth::User()
                ->temporaryUploadImageToFolderFromCloudinaryNotification(
                    $cloudinaryNotificationUrlRequestData,
                    $file_upload_directory
                );

    }

    /**
     * @return TemporaryUploadedImages
     */
    public function temporaryUploadMobileOfferImageFromCloudinaryNotification(CloudinaryNotificationUrlRequestData $cloudinaryNotificationUrlRequestData)
    {

        return
            Auth::user()
                ->temporaryUploadImageToFolderFromCloudinaryNotification(
                    $cloudinaryNotificationUrlRequestData,
                    FileUploadDirectory::MOBILE_OFFERS
                );

    }

    /**
     * @return TemporaryUploadedImages
     *
     * @throws FailedToDeleteImageException
     */
    public function deleteFileByPublicId(string $public_id)
    {

        $public_id =
             str_replace(
                 '-',
                 '/',
                 $public_id
             );

        DB::beginTransaction();

        // if image is created before parent model is created (i.e on create page)
        TemporaryUploadedImages::query()
            ->firstWhere(
                'public_id',
                $public_id
            )
            ?->delete();

        // // if image is created after parent model is created(i.e on update page)
        Media::query()
            ->firstWhere(
                'public_id',
                $public_id
            )
            ?->delete();

        // return {resut: "ok"} in success
        // and {result: "not found"} in failure
        $delete_response =
            CloudUploadService::destroy(
                $public_id
            );

        if ($delete_response['result'] == 'not_found') {

            DB::rollBack();
            throw new FailedToDeleteImageException;
            // abort(
            //     HttpStatusCode::INTERNAL_SERVER_ERROR,
            //     'خطأ في الخادم الداخلي. يرجى المحاولة مرة أخرى لاحقًا.',
            // );
        }

        DB::commit();

    }
}
