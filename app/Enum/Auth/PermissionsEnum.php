<?php

namespace App\Enum\Auth;

use App\Enum\FileUploadDirectory;
use App\Models\MobileOffer;

enum PermissionsEnum: string
{
    // case NAMEINAPP = 'name-in-database';

    case TEST_PERMISSION = 'test permission';

    case UPLOAD_MOBILE_PICTURES = 'upload mobile pictures';

    /**
     * @return PermissionsEnum[]
     **/
    public static function get_store_permissions()
    {
        return
            [
                self::UPLOAD_MOBILE_PICTURES => [
                    'folder' => FileUploadDirectory::MOBILE_OFFERS,
                    'resource' => MobileOffer::class,
                ],
            ];
    }

    /**
     * Summary of oneOfMiddleware
     *
     * @param  PermissionsEnum[]  $roles
     */
    public static function oneOfPermissionsMiddleware(...$permissions): string
    {
        $permissions_count = count($permissions);

        $permissions_collections = collect($permissions);

        return
            $permissions_collections
                ->implode('|');

    }

    public static function onePermissionOnlyMiddleware(PermissionsEnum $permission): string
    {
        return 'permission:'.$permission->value;
    }
}
