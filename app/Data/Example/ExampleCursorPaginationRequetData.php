<?php

namespace App\Data\Example;

use App\Data\Shared\Pagination\CursorPaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema()]
class ExampleCursorPaginationRequetData extends CursorPaginationResultData
{

    /** @param Collection<int, ExampleData> $data*/
    public function __construct(
        int $per_page,
        ?string $next_cursor,
        #[ArrayProperty(ExampleData::class)]
        public Collection $data,
    ) {
        parent::__construct($per_page, $next_cursor);
    }
}
