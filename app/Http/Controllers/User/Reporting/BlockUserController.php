<?php

namespace App\Http\Controllers\User\Reporting;


use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\Reporting\BlockUser\Request\BlockUserRequestData;
use App\Http\Controllers\User\Reporting\Abstract\BlockUserAbstractController;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class BlockUserController extends BlockUserAbstractController
{

    #[OAT\Patch(path: '/users/reportings/block-user/{id}', tags: ['usersReportings'])]
    #[JsonRequestBody(BlockUserRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke( BlockUserRequestData $request)
    {

        Auth
            ::user()
            ->blocksUsers()
            ->attach($request->id);

    }
}
