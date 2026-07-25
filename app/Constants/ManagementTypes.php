<?php

class ManagementTypes
{
    const GENERIC_NAME = 1;
    const PRODUCT_TYPE = 2;
    const USERS = 3;
    const ROLES = 4;
    const BRANDS = 5;

    public static function getManagementTypes() {
        return [
            SELF::GENERIC_NAME,
            SELF::PRODUCT_TYPE,
            SELF::USERS,
            SELF::ROLES,
            SELF::BRANDS
        ];
    }
}