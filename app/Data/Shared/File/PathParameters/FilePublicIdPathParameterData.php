<?php

namespace App\Data\Shared\File\PathParameters;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class FilePublicIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'filesPublicIdPathParameterData', //the name used in ref
                name: 'public_id',
                schema: new OAT\Schema(
                    type: 'string',
                ),
            ),
            FromRouteParameter('public_id'),
        ]
        public string $public_id,
    ) {}
}
