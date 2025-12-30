<?php

namespace App\Http\Controllers;

use App\Data\Example\ExampleCursorPaginationRequetData;
use App\Data\Example\ExampleData;
use App\Data\Example\PathParameters\ExampledPathParameterData;
use App\Data\Example\QueryParameters\ExampleQueryParameterData;
use App\Data\Shared\Swagger\Parameter\QueryParameter\ListQueryParameter;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Request\FormDataRequestBody;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Models\User;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admins/admin/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/ExampleIdPathParameter',
            ),
        ],
    )
]
class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OAT\Get(path: '/admins/tests', tags: ['tests'])]
    #[SuccessItemResponse(true)]
    public function get_success_item()
    {
        //
    }

    #[OAT\Get(path: '/admins/tests/list', tags: ['tests'])]
    #[SuccessListResponse(ExampleData::class)]
    public function get_success_list()
    {
        return ExampleData::collect([]);
    }

    #[OAT\Get(path: '/admins/tests/queryParameters', tags: ['tests'])]
    #[QueryParameter('name')]
    #[ListQueryParameter()]
    #[SuccessItemResponse(ExampleData::class)]
    public function get_query_parameters(ExampleQueryParameterData $query_parameters)
    {
        return ExampleData::from([]);
    }

    #[OAT\Get(path: '/admins/tests/cursorPagination', tags: ['tests'])]
    #[QueryParameter('name')]
    #[SuccessItemResponse(ExampleCursorPaginationRequetData::class)]
    public function get_cursor_pagination(ExampleQueryParameterData $query_parameters)
    {

        $users =
            User::query()
                ->cursorPaginate(15);

        // can apply transformation using fromModel
        // or else while preserving the wrapper data field and others.
        return ExampleQueryParameterData::collect($users);
    }

    #[OAT\Post(path: '/admins/tests', tags: ['tests'])]
    #[JsonRequestBody(ExampleData::class)]
    #[SuccessNoContentResponse('User created successfully')]
    public function post_json(ExampleData $data)
    {
        //
    }

    #[OAT\Post(path: '/admins/tests/form_data', tags: ['tests'])]
    #[FormDataRequestBody(ExampleData::class)]
    #[SuccessNoContentResponse('User successfully created')]
    public function post_form_data(ExampleData $user) {}

    #[OAT\Get(path: '/admins/tests/{id}', tags: ['tests'])]
    #[SuccessItemResponse(ExampleData::class, 'Fetched item successfully')]
    public function show_item(ExampledPathParameterData $request) {}

    #[OAT\Patch(path: '/admins/tests/{id}', tags: ['tests'])]
    #[FormDataRequestBody(ExampleData::class)]
    #[SuccessNoContentResponse('Update User Successfuly')]
    public function patch_json(ExampledPathParameterData $path_parameters, ExampleData $user) {}

    #[OAT\Delete(path: '/admins/tests/{id}', tags: ['tests'])]
    #[SuccessNoContentResponse('Item Deleted Successfully')]
    public function delete_json(ExampledPathParameterData $path_parameters) {}
}
