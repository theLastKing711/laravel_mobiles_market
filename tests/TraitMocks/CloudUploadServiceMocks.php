<?php

namespace Tests\TraitMocks;

use App\Data\Shared\File\SignedRequestData;
use App\Enum\FileUploadDirectory;
use App\Exceptions\Api\Cloudinary\DuplicateSignedRequestSignature;
use App\Facades\CloudUploadService;
use Illuminate\Support\Collection;

trait CloudUploadServiceMocks
{
    public function mockSignRequest(SignedRequestData $item, FileUploadDirectory $directory, int $count = 0)
    {

        CloudUploadService::partialMock()
            ->expects('signRequest')
            ->with($directory, $count)
            ->andReturn(
                $item
            );

    }

    private function genereateSignRequestWithUniqueSignature(?array $partial_sign_request = [])
    {

        $base_sign_request = new SignedRequestData(
            timestamp: time() + fake()->numberBetween(1, 10000),
            eager: config('cloudinary.api_key'),
            folder: FileUploadDirectory::TEST_FOLDER,
            signature: fake()->sha1(),
            api_key: config('cloudinary.api_key'),
            cloud_name: config('cloudinary.cloud_name'),
        );

        return clone (
            $base_sign_request,
            $partial_sign_request
        );

    }

    private function genereateSignRequestsWithUniqueSignatures(FileUploadDirectory $directory, int $count, ?array $partial_sign_request = [])
    {
        return
            Collection::times($count, fn ($number) => $number)
                ->map(
                    fn ($value) => $this->genereateSignRequestWithUniqueSignature($partial_sign_request),
                );

    }

    private function genereateSignRequestWtihStaticSignature(?array $partial_sign_request = [])
    {

        $base_sign_request = new SignedRequestData(
            timestamp: time(),
            eager: config('cloudinary.api_key'),
            folder: FileUploadDirectory::TEST_FOLDER,
            signature: 'static signature',
            api_key: config('cloudinary.api_key'),
            cloud_name: config('cloudinary.cloud_name'),
        );

        return clone (
            $base_sign_request,
            $partial_sign_request
        );

    }

    private function genereateSignRequestsWithStaticSignatures(FileUploadDirectory $directory, int $count, ?array $partial_sign_request = [])
    {
        return
            Collection::times($count, fn ($number) => $number)
                ->map(
                    fn ($value) => $this->genereateSignRequestWtihStaticSignature($partial_sign_request),
                );

    }

    public function mockSignRequestWithUniqueSignatueNTimes(FileUploadDirectory $directory, int $count)
    {

        $items =
            $this
                ->genereateSignRequestsWithUniqueSignatures(
                    $directory,
                    $count,
                    [
                        'folder' => $directory,
                    ]
                )
                ->each(function ($item, $index) use ($directory) {
                    $this
                        ->mockSignRequest(
                            $item,
                            $directory,
                            $index
                        );
                });

        return $items;

    }

    public function mockSignRequestWithStaticSignatueNTimes(FileUploadDirectory $directory, int $count)
    {

        $items =
            $this
                ->genereateSignRequestsWithStaticSignatures(
                    $directory,
                    $count,
                    [
                        'folder' => $directory,
                    ]
                )
                ->each(function ($item, $index) use ($directory) {
                    $this
                        ->mockSignRequest(
                            $item,
                            $directory,
                            $index
                        );
                });

        return $items;

    }

    private function mockSignRequests(FileUploadDirectory $directory, int $urls_count)
    {
        return
            CloudUploadService::partialMock()
                ->expects('signRequests')
                ->with($directory, $urls_count);
    }

    public function mockSignRequestsWithUniqueSignRequestSignatures(FileUploadDirectory $directory, int $urls_count)
    {

        $urls = Collection::times($urls_count, fn ($number) => $number)
            ->map(
                fn ($value) => $this->genereateSignRequestWtihStaticSignature([
                    'folder' => $directory,
                ]),
            );

        $this
            ->mockSignRequests($directory, $urls_count)
            ->andReturn($urls);

    }

    public function mockSignRequestsThrowsDuplicateSignedRequestSignature(FileUploadDirectory $directory, int $urls_count)
    {

        $this
            ->genereateSignRequestsWithStaticSignatures(
                $directory,
                $urls_count,
                [
                    'folder' => $directory,
                ]
            );

        $this
            ->mockSignRequests($directory, $urls_count)
            ->andThrow(DuplicateSignedRequestSignature::class);

    }

    public function mockUpload($times = 1)
    {

        if ($times == 1) {

            $upload_mock_response =
                $this
                    ->getUploadMockResponse();

            CloudUploadService::shouldReceive('upload')
                ->once()
                ->andReturn(
                    $upload_mock_response
                );

            return $upload_mock_response;

        }

        $upload_mock_response =
            collect([])
                ->range(1, $times)
                ->map(
                    fn ($value) => $this->getUploadMockResponse()
                );

        CloudUploadService::partialMock()
            ->shouldReceive('upload')
            ->times($times)
            ->andReturn(
                ...$upload_mock_response->toArray()
            );

        return collect($upload_mock_response);
    }

    private function mockDestroy(string $public_id, $times = 1)
    {
        return
            CloudUploadService::partialMock()
                ->shouldReceive('destroy')
                ->with($public_id)
                ->times($times);
    }

    public function mockDestroySuccess(string $public_id, $times = 1)
    {

        $this->mockDestroy($public_id, $times)
            ->andReturn(true);
    }

    public function mockDestroyFailure(string $public_id, $times = 1)
    {

        $this->mockDestroy($public_id, $times)
            ->andReturn(false);
    }

    public function mockDestroyMultiple(Collection $images)
    {

        $images
            ->pluck('file_name')
            ->each(
                fn ($file_name) => $this->mockDestroy($file_name, 1)
            );
    }

    public function getUploadMockResponse(): array
    {
        return
        [
            'asset_id' => fake()->password(15),
            'public_id' => fake()->password(10, 12),
            'version' => fake()->randomNumber(8),
            'version_id' => 'e2d0f7ad394cf1c2bd0aeb4a116cc7cb',
            'signature' => '84d708aec3b07af6444a12453dfa06c723a050c1',
            'width' => 851,
            'height' => 2360,
            'format' => 'jpg',
            'resource_type' => 'image',
            'created_at' => '2024-10-26T13=>47=>17Z',
            'tags' => [],
            'bytes' => fake()->numberBetween(40000, 100000),
            'type' => 'upload',
            'etag' => 'ace3b53cdb09459b36e05a621b765b02',
            'placeholder' => false,
            'url' => fake()->imageUrl(),
            'secure_url' => fake()->imageUrl(),
            'folder' => '',
            'original_filename' => 'phpZnCtHW',
            'eager' => [
                [
                    'transformation' => 'w_90',
                    'width' => 90,
                    'height' => 250,
                    'bytes' => 5323,
                    'format' => 'jpg',
                    'url' => 'http=>//res.cloudinary.com/dkmsfsa7c/image/upload/w_90/v1729950437/sg8oi7r0xr3cknbddbe7.jpg',
                    'secure_url' => 'https=>//res.cloudinary.com/dkmsfsa7c/image/upload/w_90/v1729950437/sg8oi7r0xr3cknbddbe7.jpg',
                ],
                [
                    'transformation' => 'w_700',
                    'width' => 700,
                    'height' => 1941,
                    'bytes' => 148109,
                    'format' => 'jpg',
                    'url' => 'http=>//res.cloudinary.com/dkmsfsa7c/image/upload/w_700/v1729950437/sg8oi7r0xr3cknbddbe7.jpg',
                    'secure_url' => 'https://res.cloudinary.com/dkmsfsa7c/image/upload/w_700/v1729950437/sg8oi7r0xr3cknbddbe7.jpg',
                ],
            ],
            'api_key' => '379721987165773',
        ];
    }

    // mobile-offer specific mocks
    // private function mockSignMobileOfferRequests(int $urls_count)
    // {
    //     return
    //         CloudUploadService::partialMock()
    //             ->expects(something: 'signMobileOffersRequests')
    //             ->with($urls_count);
    // }

    // public function mockSignMobileOffersRequestsWithStaticSignatures(int $urls_count)
    // {

    //     $urls =
    //         $this
    //             ->genereateSignRequestsWithStaticSignatures(
    //                 FileUploadDirectory::MOBILE_OFFERS,
    //                 $urls_count,
    //                 [
    //                     'folder' => FileUploadDirectory::MOBILE_OFFERS,
    //                 ]
    //             );

    //     $this
    //         ->mockSignMobileOfferRequests($urls_count)
    //         ->andReturn(
    //             $urls
    //         );
    // }

    // public function mockSignMobileOffersRequestsWithUniqueSignatures(int $urls_count)
    // {

    //     $urls =
    //         $this
    //             ->genereateSignRequestsWithUniqueSignatures(
    //                 FileUploadDirectory::MOBILE_OFFERS,
    //                 $urls_count,
    //                 [
    //                     'folder' => FileUploadDirectory::MOBILE_OFFERS,
    //                 ]
    //             );

    //     $this
    //         ->mockSignMobileOfferRequests($urls_count)
    //         ->andThrow(DuplicateSignedRequestSignature::class)
    // }
}
