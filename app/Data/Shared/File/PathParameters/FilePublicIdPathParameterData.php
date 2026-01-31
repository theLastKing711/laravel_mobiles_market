<?php

namespace App\Data\Shared\File\PathParameters;

use Error;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

use function App\Helpers\getUrlToDbPublicIdFormat;

#[TypeScript]
#[Oat\Schema()]
class FilePublicIdPathParameterData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'filesPublicIdPathParameterData', // the name used in ref
                name: 'public_id',
                schema: new OAT\Schema(
                    type: 'string',
                ),
            ),
            FromRouteParameter('public_id'),
        ]
        public string $public_id,
    ) {}

    // Transform data before validation and casting
    public static function prepareForPipeline(array $payload): array
    {

        $request_public_id =
                request()
                    ->route(
                        'public_id'
                    );

        if (! isset($request_public_id)) {
            throw new Error('error');
        }

        $payload['public_id'] =
             getUrlToDbPublicIdFormat(
                 $request_public_id
             );

        return $payload;

    }
}
