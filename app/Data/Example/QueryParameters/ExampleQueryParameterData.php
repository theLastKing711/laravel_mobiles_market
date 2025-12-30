<?php
namespace App\Data\Example\QueryParameters;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class ExampleQueryParameterData extends Data
{
    /** @param Collection<int, int> $ids*/
    public function __construct(
        public ?string $name,
        public array $ids = [],
    ) {
    }
}
