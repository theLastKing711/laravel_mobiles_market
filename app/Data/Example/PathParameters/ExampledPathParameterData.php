<?php

namespace App\Data\Example\PathParameters;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class ExampledPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'ExampleIdPathParameter', //the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists(User::class)
        ]
        public int $id,
    ) {
    }
}
