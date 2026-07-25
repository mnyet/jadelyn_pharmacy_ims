<?php

class UserRoles
{
    const ADMIN = 1;
    const ENCODER   = 2;

    public static function getSearchTypes() {
        return [
            SELF::ADMIN,
            SELF::ENCODER,
        ];
    }
}