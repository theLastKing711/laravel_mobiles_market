<?php

namespace App\Data\Shared\File\PathParameters;

use App\Models\Media;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class MediaPublicIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'filesMediaPublicIdPathParameterData', // the name used in ref
                name: 'public_id',
                schema: new OAT\Schema(
                    type: 'string',
                ),
            ),
            FromRouteParameter('public_id'),
            Exists(Media::class, 'public_id')
        ]
        public string $public_id,
    ) {}
}
