<?php

class SearchTypes
{
    const BRAND_NAME = 1;
    const PRODUCT_TYPE   = 2;
    const GENERIC_NAME = 3;
    const LOT_NUMBER = 4;

    public static function getSearchTypes() {
        return [
            SELF::BRAND_NAME,
            SELF::PRODUCT_TYPE,
            SELF::GENERIC_NAME,
            SELF::LOT_NUMBER,
        ];
    }
}