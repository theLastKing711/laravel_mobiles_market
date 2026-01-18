<?php

namespace App\Helpers\RotueBuilder;

use Illuminate\Support\Collection;

class RouteBuilder
{
    private string $route = '';

    private bool $question_mark_used = false;

    private function __construct(string $main_route)
    {
        $this->route = $main_route;
    }

    /**
     * Create a new class instance.
     */
    public static function withMainRoute(string $root_url)
    {
        return new RouteBuilder($root_url);
    }

    /** @param array<int|string> $paths */
    public function withPaths(...$paths)
    {

        $route_paths = collect($paths)
            ->reduce(
                function ($prev, $curr, $index) {
                    return $prev.'/'.(string) $curr;
                },
                ''
            );

        $this->route =
            $this->route
            .
            $route_paths;

        return $this;

    }

    /** @param array<string, int|string> query_parameters */
    public function withQueryParameters(array $query_parameters)
    {

        $query_parameters = collect(
            $query_parameters
        )
            ->reduce(
                function ($prev, $curr, $key) {

                    $query_parameter_key = $key;

                    $query_parameter_value = $curr;

                    $query_parameter = $query_parameter_key.'='.$query_parameter_value;

                    if (! $this->question_mark_used) {
                        $this->question_mark_used = true;

                        return $prev.'?'.$query_parameter;
                    }

                    return $prev.'&'.$query_parameter;
                },
                ''
            );

        $this->route =
            $this
                ->route
                .
                $query_parameters;

        return $this;

    }

    /**
     * generates an array query parameters for example ?ids[]1&ids[]=2
     */
    /** @param Collection<string>|array<string> $array_query_parameters_values */
    public function withArrayQueryParameter(Collection|array $array_query_parameters_values, string $array_query_parameter_name = 'ids')
    {

        $array_query_parameter_name_with_brackets = $array_query_parameter_name.'[]';

        $query_parameters_string =
            collect($array_query_parameters_values)
                ->reduce(
                    function ($prev, $curr, $index) use ($array_query_parameter_name_with_brackets) {
                        if (! $this->question_mark_used) {

                            $this->question_mark_used = true;

                            return $prev.'?'.$array_query_parameter_name_with_brackets.'='.$curr;
                        }

                        return $prev.'&'.$array_query_parameter_name_with_brackets.'='.$curr;
                    },
                    ''
                );

        $this->route =
            $this->route
            .
            $query_parameters_string;

        return $this;

    }

    public function build(): string
    {
        $this->question_mark_used = false;

        return $this->route;
    }
}
