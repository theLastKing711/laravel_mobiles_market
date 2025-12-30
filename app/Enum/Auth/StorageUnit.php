<?php

namespace App\Enum\Auth;

enum StorageUnit: string
{
    case GIGA_BYTE = 'gb';

    case TERA_BYTE = 'tb';

    public function label(): string
    {
        return match ($this) {
            self::GIGA_BYTE => 'giga byte',
            self::TERA_BYTE => 'tera byte',
        };
    }
}
