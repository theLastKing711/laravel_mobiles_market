<?php

namespace App\Constants\Routes;

class FileRoute
{
    public const MAIN_ROUTE = 'files';

    public const SUB_ROUTES = [
        '/cloudinary-presigned-urls' => '/cloudinary-presigned-urls',
        '/cloudinary-notifications-url' => '/cloudinary-notifications-url',
        '/media/{public_id}' => '/media/{public_id}',
        '/temporary-uploaded-images/{public_id}' => '/temporary-uploaded-images/{public_id}',
    ];

    public const ROUTES = [
        'files.cloudinary-presigned-urls' => [
            'name' => 'files.cloudinary-presigned-urls',
            'route' => self::MAIN_ROUTE.self::SUB_ROUTES['/cloudinary-presigned-urls'],
        ],
        'files.cloudinary-notifications-url' => [
            'name' => 'files.cloudinary-notifications-url',
            'route' => self::MAIN_ROUTE.self::SUB_ROUTES['/cloudinary-notifications-url'],
        ],
        'files.media.{public_id}.delete' => [
            'name' => 'files.media.{public_id}.delete',
            'route' => self::MAIN_ROUTE.'/media/{public_id}'.self::SUB_ROUTES['/media/{public_id}'],
        ],
        'files.temporary-uploaded-images.{public_id}.delete' => [
            'name' => 'files.temporary-uploaded-images.{public_id}.delete',
            'route' => self::MAIN_ROUTE.self::SUB_ROUTES['/temporary-uploaded-images/{public_id}'],
        ],
    ];
}
