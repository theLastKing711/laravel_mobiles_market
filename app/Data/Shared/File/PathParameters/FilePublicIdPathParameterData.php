<?php

namespace App\Data\Shared\File\PathParameters;

use App\Models\TemporaryUploadedImages;
use Error;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

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
                $payload['public_id'];

        if (! isset($request_public_id)) {
            throw new Error('error');
        }

        $payload['public_id'] =
             str_replace(
                 '-',
                 '/',
                 $request_public_id
             );

    }

    // Exists(TemporaryUploadedImages::class, 'public_id')

    // public static function rules(?ValidationContext $context = null): array
    // {
    //     $request_public_id =
    //         $context
    //             ->payload['public_id'];

    //     $request_parsed_public_id =
    //          str_replace(
    //              '-',
    //              '/',
    //              $request_public_id
    //          );

    //     $public_id_exist =
    //         TemporaryUploadedImages::firstWhere(
    //             'public_id',
    //             $request_parsed_public_id
    //         );

    //     if (! $public_id_exist) {
    //         return [
    //             'public_id' => [
    //                 'required',
    //             ],
    //         ];
    //     }

    //     return [];

    // }
}
