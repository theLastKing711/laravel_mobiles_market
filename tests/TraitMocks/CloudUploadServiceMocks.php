<?php

namespace Tests\TraitMocks;

use App\Enum\FileUploadDirectory;
use App\Facades\CloudUploadService;
use App\Services\CloudinaryService;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;

trait CloudUploadServiceMocks
{
    public function mockSignRequest()
    {
        $this->instance(
            CloudinaryService::class,
            Mockery::mock(CloudinaryService::class, function (MockInterface $mock) {
                $mock
                    ->expects('signRequest')
                    ->andReturn(
                        [
                            'signature' => fake()->sha1(),
                            'api_key' => config('cloudinary.api_key'),
                            'cloud_name' => config('cloudinary.cloud_name'),
                            'timestamp' => fake()->time(),
                            'eager' => 't_thumbnail|t_main',
                            'folder' => fake()->word(),
                        ]
                    );
            })
        );

    }

    public function mockSignRequestWithStaticReturn(FileUploadDirectory $directory, $index = 0)
    {
        $this->instance(
            CloudinaryService::class,
            Mockery::mock(CloudinaryService::class, function (MockInterface $mock) use ($directory, $index) {
                $mock
                    ->expects('signRequest')
                    ->with($directory, $index)
                    ->andReturn(
                        [
                            'signature' => 'static_signature_for_tests',
                            'api_key' => config('cloudinary.api_key'),
                            'cloud_name' => config('cloudinary.cloud_name'),
                            'timestamp' => 'static_time_stamp',
                            'eager' => 't_thumbnail|t_main',
                            'folder' => fake()->word(),
                        ]
                    );
            })
        );

    }

    public function mockSignRequestsWithStaticReturn(FileUploadDirectory $directory, int $urls_count)
    {

        // Collection::times($urls_count, fn ($number) => $number)
        //     ->each(
        //         fn ($value) => $this->mockSignRequestStatic($directory, $value)
        //     );

        $urls = Collection::times($urls_count, fn ($number) => $number)
            ->map(
                fn ($value) => [
                    'signature' => 'static_signature_for_tests',
                    'api_key' => config('cloudinary.api_key'),
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'timestamp' => 'static_time_stamp',
                    'eager' => 't_thumbnail|t_main',
                    'folder' => $directory,
                ],
            );

        $this->instance(
            CloudinaryService::class,
            Mockery::mock(CloudinaryService::class, function (MockInterface $mock) use ($directory, $urls, $urls_count) {
                $mock
                    ->expects('signRequests')
                    ->with($urls_count, $directory)
                    ->andReturn(
                        $urls
                    );
            })
        );

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

        CloudUploadService::shouldReceive('upload')
            ->times($times)
            ->andReturn(
                ...$upload_mock_response->toArray()
            );

        return collect($upload_mock_response);
    }

    // public id is also $media->file_name
    public function mockDestroy(string $public_id, $times = 1)
    {

        CloudUploadService::shouldReceive('destroy')
            ->with($public_id)
            ->times($times);
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
}
