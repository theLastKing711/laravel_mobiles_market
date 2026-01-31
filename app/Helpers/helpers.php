<?php

namespace App\Helpers;

if (! function_exists('getUrlToDbPublicIdFormat')) {
    /**
     * @return string
     */
    function getUrlToDbPublicIdFormat(string $public_id)
    {
        return
            str_replace(
                '-',
                '/',
                $public_id
            );
    }
}
