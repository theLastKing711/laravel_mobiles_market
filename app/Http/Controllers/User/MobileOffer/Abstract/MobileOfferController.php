<?php

namespace App\Http\Controllers\User\MobileOffer\Abstract;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Schema;

#[
    OAT\PathItem(
        path: '/users/mobile-offers/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/usersGetMobileOfferRequestPathParameterData',
            ),
        ],
    ),
    Schema(schema: 'UserMobileOfferController')
]
abstract class MobileOfferController extends Controller {}
