<?php

namespace App\Enum;

use App\Trait\EnumHelper;
use OpenApi\Attributes as OAT;

#[OAT\Schema()]
enum CloudinaryTransformationEnum: string
{
    case THUMBNAIL = 't_thumbnail';
    case MAIN = 't_main';

    use EnumHelper;
}
