<?php

namespace App\Data\User\Reporting\BlockUser\Request;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;

class BlockUserRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'usersBlockUserRequestPathParameterData', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists('users', 'id')
        ]
        public int $id,
    ) {
    }
}
