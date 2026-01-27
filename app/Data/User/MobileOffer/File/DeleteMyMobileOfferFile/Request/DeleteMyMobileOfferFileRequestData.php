<?php

namespace App\Data\User\MobileOffer\File\DeleteMyMobileOfferFile\Request;

use App\Models\Media;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class DeleteMyMobileOfferFileRequestData extends Data
{
    public function __construct(
        #[
            OAT\PathParameter(
                parameter: 'usersDeleteMyMobileOfferFileRequestPathParameterData', // the name used in ref
                name: 'id',
                schema: new OAT\Schema(
                    type: 'integer',
                ),
            ),
            FromRouteParameter('id'),
            Exists(Media::class, 'id')
        ]
        public int $id,
    ) {}
}
