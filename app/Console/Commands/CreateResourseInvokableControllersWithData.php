<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateResourseInvokableControllersWithData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     *             example
     *             php artian make:resourse-data-controller Admin\Product --with-pagination
     */
    protected $signature = 'make:resourse-data-controller {path} {--with-pagination} {--post==} {--patch==} {--get-one==} {--get-many==} {--delete-one==} {--without-post} {--without-get-one} {--without-get-many} {--without-patch} {--without-delete-one}';

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

        $path =
            $this->argument('path');

        [$role, $resourse] =
            explode('\\', $path);

        $this
            ->genereateBaseAbstractClass($path, $resourse);

        if ($this->option('with-pagination') && ! $this->option('without-get-many')) {
            $this
                ->generateGetManyWithPaginationController($path, $resourse, $this->option('get-many'));
        } else {
            $this
                ->generateGetManyController($path, $resourse, $this->option('get-many'));
        }

        if (! $this->option('without-get-one')) {
            $this
                ->generateGetController($path, $resourse, $this->option('get-one'));
        }

        if (! $this->option('without-post')) {
            $this
                ->generatePostController($path, $resourse, $this->option('post'));
        }

        if (! $this->option('without-patch')) {
            $this
                ->generatePatchController($path, $resourse, $this->option('patch'));
        }

        if (! $this->option('without-delete-one')) {
            $this
                ->generateDeleteController($path, $resourse, $this->option('delete-one'));
        }

    }

    private function genereateBaseAbstractClass($path, $resourse)
    {
        $abstract_name =
                $path
                    .'\\'
                    .'Abstract'
                    .'\\'
                    ."{$resourse}";

        Artisan::call('make:data-controller', [
            'name' => $abstract_name,
            '--abstract' => true,
        ]);
    }

    private function generateGetManyController($path, $resourse, ?string $action)
    {
        $resourse_plural =
            Str::plural(
                $resourse
            );

        $action =
            $action ??
            'Get'.$resourse_plural;

        $get_name =
            $path
                .'\\'
                .$action;

        $get_response_data =
            $get_name
                .'\\'
                .'Response'
                .'\\'
                ."{$action}Response";

        Artisan::call('make:data-controller', [
            'name' => $get_name,
            '--get-many' => $get_response_data,
        ]);
    }

    private function generateGetManyWithPaginationController($path, $resourse, ?string $action)
    {

        $resourse_plural =
            Str::plural(
                $resourse
            );

        $action =
            $action ??
            'Get'.$resourse_plural;

        $get_name =
            $path
                .'\\'
                .$action;

        $get_response_data =
            $get_name
                .'\\'
                .'Response'
                .'\\'
                ."{$action}Response";

        $pagination_name =
            $path
                .'\\'
                ."{$action}";

        $pagination_request_data =
            $pagination_name
                .'\\'
                .'Request'
                .'\\'
                ."{$action}Request";

        Artisan::call('make:data-controller', [
            'name' => $get_name,
            '--get-many' => $get_response_data,
            '--pagination' => $pagination_request_data,
        ]);

    }

    private function generateGetController($path, $resourse, $action)
    {

        $action =
            $action ??
            'Get'.$resourse;

        $get_name =
            $path
                .'\\'
                ."{$action}";

        $get_response_data =
            $get_name
                .'\\'
                .'Response'
                .'\\'
                ."{$action}Response";

        $get_request_data =
            $get_name
                .'\\'
                .'Request'
                .'\\'
                ."{$action}Request";

        Artisan::call('make:data-controller', [
            'name' => $get_name,
            '--get-one' => $get_response_data,
            '--request' => $get_request_data,
        ]);
    }

    private function generatePostController($path, $resourse, $action)
    {

        $action =
            $action ??
            'Create'.$resourse;

        $post_name =
            $path
                .'\\'
                ."{$action}";

        $post_request_data =
            $post_name
                .'\\'
                .'Request'
                .'\\'
                ."{$action}Request";

        Artisan::call('make:data-controller', [
            'name' => $post_name,
            '--post' => $post_request_data,
        ]);
    }

    private function generatePatchController($path, $resourse, $action)
    {

        $action =
            $action ??
            'Update'.$resourse;

        $patch_name =
            $path
                .'\\'
                ."{$action}";

        $patch_request_data =
            $patch_name
                .'\\'
                .'Request'
                .'\\'
                ."{$action}Request";

        Artisan::call('make:data-controller', [
            'name' => $patch_name,
            '--patch' => $patch_request_data,
        ]);
    }

    private function generateDeleteController($path, $resourse, $action)
    {

        $action =
           $action ??
           'Delete'.$resourse;

        $delete_name =
            $path
                .'\\'
                ."{$action}";

        $delete_request_data =
            $delete_name
                .'\\'
                .'Request'
                .'\\'
                ."{$action}Request";

        Artisan::call('make:data-controller', [
            'name' => $delete_name,
            '--delete-one' => $delete_request_data,
        ]);
    }
}
