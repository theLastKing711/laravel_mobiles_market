<?php

namespace App\Http\Controllers\User\Reporting\Abstract;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Schema;

#[
    OAT\PathItem(
        path: '/users/reportings/flag-mobile-offer/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/usersFlagMobileOfferRequestPathParameterData',
            ),
        ],
    ),
    Schema(schema: 'FlagMobileOfferAbstractController')
]
abstract class FlagMobileOfferAbstractController extends Controller {}
