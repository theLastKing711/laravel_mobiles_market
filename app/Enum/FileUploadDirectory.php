<?php

namespace App\Enum;

enum FileUploadDirectory: string
{
    // case PROJECT_DIRECTORY = 'mobiles_market';

    case USER_PROFILE_PICTURE = 'mobiles_market/profile_picture';

    case MOBILE_OFFERS = 'mobiles_market/mobile_offers';

    case TEST_FOLDER = 'test_folder';

    // case DRIVER = 'driver';

    // case STORE = 'store';

    // extra helper to allow for greater customization of displayed values,
    // without disclosing the name/value data directly
    // can be used like this: RolesEnum::ADMIN->label() which return 'Admin'
    // public function label(): string
    // {

    //     return match ($this) {
    //         self::USER_PROFILE_PICTURE => 'User Profile Picture',
    //         self::SCHOOL_FILES => 'School Files',
    //     };
    // }
}
