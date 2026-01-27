<?php

namespace App\Http\Controllers\User\MobileOffer\File\Abstract;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Schema;

#[
    OAT\PathItem(
        path: '/users/my-mobile-offers/files/{public_id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/usersGetMobileOfferRequestPathParameterData',
            ),
        ],
    ),
    Schema(schema: 'UserMyMobileOfferOfferController')
]
abstract class MyMobileOfferFileControllerAbstract extends Controller {}
