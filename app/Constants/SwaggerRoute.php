<?php

namespace App\Constants;

class SwaggerRoute
{
    public const FILE = [
        'MAIN_ROUTE' => '/users/my-mobile-offers/files',
        'CHILD_ROUTES' => [
            'cloudinary-presigned-urls' => '/cloudinary-presigned-urls',
            'cloudinary-notifications-url' => '/cloudinary-notifications-url',
            'media' => '/medias/{public_id}',
            'temporary-uploaded-images' => '/temporary-uploaded-images/{public_id}',
        ],
    ];

    public const MAIN_ROUTES = [
        'files' => '/users/my-mobile-offers/files',
    ];

    public const MY_MOBILE_OFFER_FILE = [
        'MAIN_ROUTE' => '/users/my-mobile-offers/files',
        'CHILD_ROUTES' => [
            'cloudinary-presigned-urls' => self::MY_MOBILE_OFFER_FILE['CHILD_ROUTES'].'/cloudinary-presigned-urls',
            'cloudinary-notifications-url' => '/cloudinary-notifications-url',
            'media' => '/medias/{public_id}',
            'temporary-uploaded-images' => '/temporary-uploaded-images/{public_id}',
        ],
    ];
}
