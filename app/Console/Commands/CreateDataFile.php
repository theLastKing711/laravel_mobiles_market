<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateDataFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:data {name} {--path} {--delete-many} {--pagination==}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel-Spatie-Data File';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        /** @var string $path */
        $path =
            str_replace(
                '/',
                '\\',
                $this->argument('name')
            );

        $class_name =
            explode(
                '\\',
                $path
            );

        $augmented_path =
            explode(
                '\\',
                $path
            );

        array_splice($augmented_path, -1, 1);

        $real_path = implode('\\', array: $augmented_path);

        $schemaName =
               str_replace('\\', '', $real_path);

        $path_option = $this->option('path');

        if ($path_option) {

            $main_route =
            Str::plural(
                strtolower(
                    strtolower(

                        $class_name[0]
                    )
                )
            );
            // strtolower(
            //
            // ).'s';

            $file_name =
                $class_name[count(value: $class_name) - 1];

            $file_class_name =
                $file_name.'Data';

            $ref =
                $main_route.$file_name.'PathParameterData';

            // $resource_name =
            // Str::plural(
            //     strtolower(
            //         $class_name[count(value: $class_name) - 4]
            //     )
            // );

            $resource_name =
                Str::plural(strtolower(convertCamelCaseData($class_name[1])));

            $fileContents = <<<EOT
            <?php

            namespace App\Data\\$real_path;

            use Spatie\LaravelData\Attributes\Validation\Exists;
            use Spatie\LaravelData\Data;
            use OpenApi\Attributes as OAT;
            use Spatie\LaravelData\Attributes\FromRouteParameter;

            class $file_class_name extends Data
            {
                public function __construct(
                    #[
                        OAT\PathParameter(
                            parameter: '$ref', //the name used in ref
                            name: 'id',
                            schema: new OAT\Schema(
                                type: 'integer',
                            ),
                        ),
                        FromRouteParameter('id'),
                        Exists('$resource_name', 'id')
                    ]
                    public int \$id,
                ) {
                }
            }

            EOT;

            $written = Storage::disk('app')
                ->put('Data'.'\\'.$this->argument('name').'Data.php', $fileContents);

            if ($written) {
                $this->info('Created new Repo '.$this->argument('name').'Repository.php in App\Repositories.');
            } else {
                $this->info('Something went wrong');
            }

            return;
        }

        $file_class_name_without_data =
            $class_name[count($class_name) - 1];

        $file_class_name =
            $file_class_name_without_data.'Data';

        $pagination_option = $this->option('pagination');

        if ($pagination_option) {

            // /** @var string $path */
            // $response_path =
            //     str_replace(
            //         '/',
            //         '\\',
            //         $this->option('pagination')
            //     );

            // $response_class_name =
            //     explode(
            //         '\\',
            //         $response_path
            //     );

            // $response_augmented_path =
            //     explode(
            //         '\\',
            //         $response_path
            //     );

            // array_splice($response_augmented_path, -1, 1);

            // $response_path = implode('\\', $response_augmented_path);

            // $response_file_class_name =
            //     $file_class_name_without_data.'Data';

            // $fileContents_3 = <<<EOT
            // <?php

            // namespace App\Data\\$response_path;
            // use Spatie\LaravelData\Data;
            // use Spatie\TypeScriptTransformer\Attributes\TypeScript;
            // use OpenApi\Attributes as OAT;

            // #[TypeScript]
            // #[Oat\Schema()]
            // class $response_file_class_name extends Data
            // {
            //     public function __construct(

            //     ) {
            //     }
            // }

            // EOT;

            /** @var string $path */
            $pagination_path =
                str_replace(
                    '/',
                    '\\',
                    $this->option('pagination')
                );

            $pagination_class_name =
                explode(
                    '\\',
                    $pagination_path
                );

            $pagination_augmented_path =
                explode(
                    '\\',
                    $pagination_path
                );

            array_splice($pagination_augmented_path, -1, 1);

            $pagination_path = implode('\\', $pagination_augmented_path);

            $pagination_path_file_name_without_data =
                $pagination_class_name[count($pagination_class_name) - 1];

            $pagination_file_class_name =
                $pagination_path_file_name_without_data.'Data';

            $fileContents_2 = <<<EOT
            <?php

            namespace App\Data\\$pagination_path;

            use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
            use OpenApi\Attributes as OAT;

            #[Oat\Schema()]
            class $pagination_file_class_name extends PaginationQueryParameterData
            {
                public function __construct(
                    ?int \$page,
                    ?int \$perPage,
                ) {
                    parent::__construct(\$page, \$perPage);
                }
            }

            EOT;

            $written = Storage::disk('app')
                ->put('Data'.'\\'.$pagination_path.'\\'.$pagination_file_class_name.'.php', $fileContents_2);

            if ($written) {
                $this->info('Created new Repo '.$this->argument('name').'Repository.php in App\Repositories.');
            } else {
                $this->info('Something went wrong');
            }

            // /** @var string $path */
            // $response_path =
            //     str_replace(
            //         '/',
            //         '\\',
            //         $this->option('pagination')
            //     );

            // $response_class_name =
            //     explode(
            //         '\\',
            //         $response_path
            //     );

            // $response_augmented_path =
            //     explode(
            //         '\\',
            //         $response_path
            //     );

            // array_splice($response_augmented_path, -1, 1);

            // $response_path = implode('\\', $response_augmented_path);

            // $response_file_class_name =
            //     $file_class_name_without_data.'Data';

            $response_item_file_class_name =
               $file_class_name_without_data.'Data';

            $fileContents_3 = <<<EOT
            <?php

            namespace App\Data\\$real_path;

            use Spatie\LaravelData\Data;
            use Spatie\TypeScriptTransformer\Attributes\TypeScript;
            use OpenApi\Attributes as OAT;

            #[TypeScript]
            #[Oat\Schema(schema: '$schemaName$response_item_file_class_name')]
            class $response_item_file_class_name extends Data
            {
                public function __construct(

                ) {
                }
            }

            EOT;

            $written = Storage::disk('app')
                // ->put('Data'.'\\'.$pagination_path.'\\'.$response_item_file_class_name.'.php', $fileContents_3);
                ->put('Data'.'\\'.$this->argument('name').'Data.php', $fileContents_3);

            if ($written) {
                $this->info('Created new Repo '.$this->argument('name').'Repository.php in App\Repositories.');
            } else {
                $this->info('Something went wrong');
            }

            $file_class_name =
                $file_class_name_without_data.'PaginationResultData';

            $child_class_name = $file_class_name_without_data.'Data';

            $fileContents = <<<EOT
            <?php

            namespace App\Data\\$real_path;

            use App\Data\Shared\Pagination\PaginationResultData;
            use App\Data\Shared\Swagger\Property\ArrayProperty;
            use Illuminate\Support\Collection;
            use OpenApi\Attributes as OAT;
            use Spatie\TypeScriptTransformer\Attributes\TypeScript;


            #[TypeScript]
            #[Oat\Schema(schema: '$schemaName$file_class_name')]
            class $file_class_name  extends PaginationResultData
            {
                /** @param Collection<int, $child_class_name> \$data */
                public function __construct(
                    int \$current_page,
                    int \$per_page,
                    #[ArrayProperty($child_class_name::class)]
                    public Collection \$data,
                    int \$total,
                ) {
                    parent::__construct(\$current_page, \$per_page, \$total);
                }
            }


            EOT;

            $written = Storage::disk('app')
                ->put('Data'.'\\'.$this->argument('name').'PaginationResultData.php', $fileContents);

            // $file_class_name =
            //     $file_class_name_without_data.'QueryParameterData';

            // $real_path = $real_path.'\\'.'QueryParameters';

            return;
        }

        if ($this->option('delete-many')) {
            $collection_import =
                'use Illuminate\Support\Collection';

            $array_property_import =
             'use App\Data\Shared\Swagger\Property\ArrayProperty;';

            $delete_many_data =
            '#[ArrayProperty]
            public Collection $ids,';

            $fileContents = <<<EOT
            <?php

            namespace App\Data\\$real_path;

            $array_property_import;
            $collection_import;
            use Spatie\LaravelData\Data;
            use Spatie\TypeScriptTransformer\Attributes\TypeScript;
            use OpenApi\Attributes as OAT;


            #[TypeScript]
            #[Oat\Schema(schema: '$schemaName$file_class_name')]
            class $file_class_name extends Data
            {
                public function __construct(
                    $delete_many_data
                ) {}

            }

            EOT;

            $written = Storage::disk('app')
                ->put('Data'.'\\'.$this->argument('name').'Data.php', $fileContents);

            if ($written) {
                $this->info('Created new Repo '.$this->argument('name').'Repository.php in App\Repositories.');
            } else {
                $this->info('Something went wrong');

            }

            return;
        }

        $fileContents = <<<EOT
        <?php

        namespace App\Data\\$real_path;

        use Spatie\LaravelData\Data;
        use Spatie\TypeScriptTransformer\Attributes\TypeScript;
        use OpenApi\Attributes as OAT;

        #[TypeScript]
        #[Oat\Schema(schema: '$schemaName$file_class_name')]
        class $file_class_name extends Data
        {
            public function __construct(

            ) {}

        }

        EOT;

        $written = Storage::disk('app')
            ->put('Data'.'\\'.$this->argument('name').'Data.php', $fileContents);

        if ($written) {
            $this->info('Created new Repo '.$this->argument('name').'Repository.php in App\Repositories.');
        } else {
            $this->info('Something went wrong');
        }
    }

    // private function pluralize($word)
    // {
    //     $irregulars = [
    //         'company' => 'companies',
    //     ];

    //     if (array_key_exists($word, $irregulars)) {
    //         return $irregulars[$word];
    //     }

    //     return $word.'s';

    // }
}

function convertCamelCaseData(string $string)
{

    // $string = $string1.Str::plural($string2);

    $data = preg_split('/(?=[A-Z])/', $string);

    unset($data[0]);

    if (count($data) == 1) {
        return Str::plural(
            strtolower(
                $string
            )
        );
    }

    $string = implode(' ', $data);

    return Str::plural(strtolower(str_replace(' ', '_', $string)));
}
