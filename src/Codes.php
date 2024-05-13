<?php

namespace goodben\banking\Core;

/**
* Class Codes.
* 
* @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
class Codes 
{
    const SYSTEM_VALIDATOR = "SYSTEM";

    const OPERATION_TYPE_CASH_IN = "C";
    const OPERATION_TYPE_CASH_OUT = "D";

    const AUTH_STATUS_PENDING = "P";
    const AUTH_STATUS_ACCEPTED = "A";
    const AUTH_STATUS_REFUSED = "R";

    const PLATFORM_MODE_BANK= 'BANK';
    const PLATFORM_MODE_MERCHANT= 'MERCHANT';
    const PLATFORM_MODE_MARKETPLACE= 'MARKETPLACE';
    const PLATFORM_MODE_AGGREGATOR= 'AGGREGATOR';

    public static function getAvailableModes(): array {
        return [
            'Bank' => static::PLATFORM_MODE_BANK,
            'Merchant' => static::PLATFORM_MODE_MERCHANT,
            'Marketplace' => static::PLATFORM_MODE_MARKETPLACE,
            'Aggregator' => static::PLATFORM_MODE_AGGREGATOR,
        ];
    }
}
