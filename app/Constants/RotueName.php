<?php

namespace App\Constants;

use App\Models\User;
use Illuminate\Support\Arr;

class RotueName
{
    // public const FILE = 'files';

    public const FILE = [
        'MAIN_ROUTE' => 'files',
        'CHILD_ROUTES' => [
            'index' => 'files',
            'cloudinary-presigned-urls' => 'files'.'/cloudinary-presigned-urls',
            'cloudinary-notifications-url' => 'files'.'/cloudinary-notifications-url',
        ],
    ];

    public const ADMIN = 'admins';

    // public const USER = [
    //     'MAIN_ROUTE' => 'users',
    //     'CHILD_ROUTES' => [
    //         'index' => 'users',
    //         'auth' => [
    //             'get-user-phone-number' => 'users'.'/get-user-phone-number',
    //             'change-password' => 'users'.'/change-password',
    //             'change-phone-number' => 'users'.'/change-phone-number',
    //         ],
    //         'my-mobile-offers' => [
    //             'files' => [
    //                 'cloudinary-presigned-urls' => 'users'.'/my-mobile-offers/files/cloudinary-presigned-urls',
    //                 'cloudinary-notifications-url' => 'users'.'/my-mobile-offers/files/cloudinary-notifications-url',
    //                 '{public_id}' => 'users'.'/my-mobile-offers/files/{public_id}',
    //             ],
    //         ],
    //         'mobile-offers' => [
    //             'search' => 'users'.'/mobile-offers/search',
    //             'favourites' => 'users'.'/mobile-offers/favourites',
    //             '{id}' => 'users'.'/mobile-offers/{id}',
    //             '{id}/favourite' => 'users'.'/mobile-offers/{id}/favourite',
    //         ],
    //         'mobile-offer-features' => [
    //             'list' => 'users'.'/mobile-offer-features/list',
    //         ],
    //     ],
    // ];

    public const USER = [
        'main_route' => 'users',
        'users' => [
            'index' => 'users',
            'auth' => [
                'get-user-phone-number',
                'change-password',
                'change-phone-number',
            ],
            'my-mobile-offers' => [
                'files' => [
                    'cloudinary-presigned-urls',
                    'cloudinary-notifications-url',
                    '{public_id}',
                ],
            ],
            'mobile-offers' => [
                'search',
                'favourites',
                '{id}' => [
                    'index' => '',
                    'favourite',
                ],
            ],
            'mobile-offer-features' => [
                'list',
            ],
        ],
    ];

    // public static function build_routes_from_keys_2(array $array, string $parent_key = ''): string
    // {

    //     $paths =
    //         implode('.')

    //     // $x = Arr::dot(static::User);

    //     // $routes = [];
    //     $route = '';

    //     foreach ($array as $key => $value) {
    //         // Build the current route path
    //         $current_route = $parent_key ? $parent_key.'/'.$key : $key;

    //         if (is_array($value)) {
    //             // If the value is an array, recursively call the function
    //             // and merge the results into the main routes array
    //             // $routes = array_merge($routes, static::build_routes_from_keys($value, $current_route));
    //             $route = static::build_routes_from_keys($value, $current_route);
    //         } else {
    //             // If the value is not an array (e.g., a string, integer, etc.),
    //             // it means we've reached the end of a path.
    //             // Add the final route string to the results
    //             // $routes[] = $current_route;
    //             $route = $current_route;
    //         }
    //     }

    //     // return $routes[0];
    //     return $route;
    // }

    // public static function build_routes_from_keys(array $array, string $parent_key = ''): string
    // {
    //     // $x = Arr::dot(static::User);

    //     // $routes = [];
    //     $route = '';

    //     foreach ($array as $key => $value) {
    //         // Build the current route path
    //         $current_route = $parent_key ? $parent_key.'/'.$key : $key;

    //         if (is_array($value)) {
    //             // If the value is an array, recursively call the function
    //             // and merge the results into the main routes array
    //             // $routes = array_merge($routes, static::build_routes_from_keys($value, $current_route));
    //             $route = static::build_routes_from_keys($value, $current_route);
    //         } else {
    //             // If the value is not an array (e.g., a string, integer, etc.),
    //             // it means we've reached the end of a path.
    //             // Add the final route string to the results
    //             // $routes[] = $current_route;
    //             $route = $current_route;
    //         }
    //     }

    //     // return $routes[0];
    //     return $route;
    // }
}
