<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OAT;

#[
    OAT\Info(version: '1', title: 'In Abstract Controller'),
    // set global security header parameter in swagger ui,
    // we must choose it (ref_to_be_used_below_csrf) in bellow attribute
    OAT\SecurityScheme(
        securityScheme: 'ref_to_be_used_below_csrf',
        type: 'apiKey',
        name: 'X-XSRF-TOKEN',
        in: 'header',
    ),
    OAT\OpenApi(security: [
        ['ref_to_be_used_below_csrf' => []],
    ]),
]
abstract class Controller
{
    //
}
