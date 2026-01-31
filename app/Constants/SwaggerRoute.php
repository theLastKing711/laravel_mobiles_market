<?php

namespace App\Constants;

class SwaggerRoute
{
    public const FILES = 'files';

    public const MY_MOBILE_OFFER_FILE = [
        'MAIN_ROUTE' => '/users/my-mobile-offers/files',
        'CHILD_ROUTES' => [
            'cloudinary-presigned-urls' => '/users/my-mobile-offers/files/cloudinary-presigned-urls',
            'cloudinary-notifications-url' => '/users/my-mobile-offers/files/cloudinary-notifications-url',
            'media' => '/users/my-mobile-offers/files/media/{public_id}',
            'temporary-uploaded-images' => '/users/my-mobile-offers/files/temporary-uploaded-image/{public_id}',

        ],
    ];
}
