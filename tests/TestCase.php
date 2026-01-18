<?php

namespace Tests;

use App\Helpers\RotueBuilder\RouteBuilder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

class TestCase extends BaseTestCase
{
    protected RouteBuilder $route_builder;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @param array<int|string> $paths */
    public function withRoutePaths(...$paths)
    {
        $this
            ->route_builder
            ->withPaths(...$paths);

        return $this;
    }

    /** @param array<string, int|string> query_parameters */
    public function withQueryParameters(array $query_parameters)
    {
        $this
            ->route_builder
            ->withQueryParameters($query_parameters);

        return $this;

    }

    /**
     * generates an array query parameters for example ?ids[]1&ids[]=2
     */
    /** @param Collection<string>|array<string> $array_query_parameters_values */
    public function withArrayQueryParameter(Collection|array $array_query_parameters_values, string $array_query_parameter_name = 'ids')
    {
        $this
            ->route_builder
            ->withArrayQueryParameter(
                $array_query_parameters_values,
                $array_query_parameter_name
            );

        return $this;
    }

    public function getJsonData(array $data = [], array $headers = [], $options = 0)
    {
        $uri =
            $this
                ->route_builder
                ->build();

        return $this->getJson(
            $uri,
            $headers,
        );
    }

    public function postJsonData(array $data = [], array $headers = [], $options = 0)
    {
        $uri =
            $this
                ->route_builder
                ->build();

        return $this->postJson(
            $uri,
            $data,
            $headers,
            $options
        );
    }

    public function patchJsonData(array $data = [], array $headers = [], $options = 0)
    {
        $uri =
            $this
                ->route_builder
                ->build();

        return $this->patchJson(
            $uri,
            $data,
            $headers,
            $options
        );
    }

    public function deleteJsonData(array $data = [], array $headers = [], $options = 0)
    {
        $uri =
            $this
                ->route_builder
                ->build();

        return $this->deleteJson(
            $uri,
            $data,
            $headers,
            $options
        );
    }
}
