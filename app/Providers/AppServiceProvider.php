<?php

namespace App\Providers;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        // makes $guarded=[] in Model unneccessary
        Model::unguard();

        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->register(BuilderMacrosServiceProvider::class);

        // for debug bar loggin
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // uncommented because it cant run more than one time without error
        // unless we delete it from url below
        // https://console.cloudinary.com/app/c-6ff3b2e607f80443e78f68039a0be2/image/manage/named?page=0
        // Cloudinary::admin()
        //     ->createTransformation(
        //         'thumbnail',
        //         [
        //             'width' => 200,
        //         ]
        //     );
        // Cloudinary::admin()
        //     ->createTransformation(
        //         'main',
        //         [
        //             'width' => 300,
        //         ]
        //     );

        Gate::define('viewPulse', function (?User $user = null) {
            // Replace '192.0.2.1' with your actual public IP address
            // $allowedIp = '192.0.2.1';

            // // Get the current request
            // $request = app(Request::class);

            // // Check if the client IP matches the allowed IP
            // return $request->ip() === $allowedIp;

            return true;

        });

    }
}
