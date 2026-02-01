<?php

namespace App\Constants;

class SwaggerRoute
{
    public const MAIN_ROUTES = [
        'files' => 'files',
        'my-mobile-offer-files' => '/users/my-mobile-offers/files',
    ];

    public const FILE = [
        'MAIN_ROUTE' => self::MAIN_ROUTES['files'],
        'cloudinary-presigned-urls' => self::MAIN_ROUTES['files'].'/cloudinary-presigned-urls',
        'cloudinary-notifications-url' => self::MAIN_ROUTES['files'].'/cloudinary-notifications-url',
        'media' => self::MAIN_ROUTES['files'].'/medias/{public_id}',
        'temporary-uploaded-images' => self::MAIN_ROUTES['files'].'/temporary-uploaded-images/{public_id}',
    ];

    public const MY_MOBILE_OFFER_FILE = [
        'MAIN_ROUTE' => self::MAIN_ROUTES['my-mobile-offer-files'],
        'cloudinary-presigned-urls' => self::MAIN_ROUTES['my-mobile-offer-files'].'/cloudinary-presigned-urls',
        'cloudinary-notifications-url' => self::MAIN_ROUTES['my-mobile-offer-files'].'/cloudinary-notifications-url',
        'media' => self::MAIN_ROUTES['my-mobile-offer-files'].'/medias/{public_id}',
        'temporary-uploaded-images' => self::MAIN_ROUTES['my-mobile-offer-files'].'/temporary-uploaded-images/{public_id}',
    ];

    const FILE_ROUTE_NAMES =
        [
            'cloudinary-presigned-urls' => 'cloudinary-presigned-urls',
            'cloudinary-notifications-url' => 'cloudinary-notifications-url',
            'medias.{public_id}' => 'medias.{public_id}',
            'files.temporary-uploaded-images' => 'files.temporary-uploaded-images',
        ];

    public const FILE_2 = [
        self::FILE_ROUTE_NAMES['cloudinary-presigned-urls'] => self::FILE['cloudinary-presigned-urls'],
        self::FILE_ROUTE_NAMES['cloudinary-notifications-url'] => self::FILE['cloudinary-notifications-url'],
        // self::FILE_ROUTE_NAMES['cloudinary-notifications-url'] => self::FILE['cloudinary-notifications-url'],
    ];

    // CONST Z = [
    //     self::FILE_2[""]
    // ]
}
