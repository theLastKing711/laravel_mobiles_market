<?php

namespace App\Http\Controllers\User\Reporting\Abstract;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Schema;

#[
    OAT\PathItem(
        path: '/users/reportings/block-user/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/usersBlockUserRequestPathParameterData',
            ),
        ],
    ),
    Schema(schema: 'BlockUserAbstractController')
]
abstract class BlockUserAbstractController extends Controller {}
