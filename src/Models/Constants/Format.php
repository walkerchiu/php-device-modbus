<?php

namespace WalkerChiu\DeviceModbus\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\DeviceModbus
 *
 * 
 */

class Format
{
    /**
     * @return Array
     */
    public static function getCodes(): array
    {
        $items = [];
        $types = self::all();
        foreach ($types as $code => $type) {
            array_push($items, $code);
        }

        return $items;
    }

    /**
     * @return Array
     */
    public static function all(): array
    {
        return [
            'Signed'   => 'Signed',
            'Unsigned' => 'Unsigned',
            'Hex'      => 'Hex',
            'Binary'   => 'Binary',

            'LongABCD' => 'Long AB CD',
            'LongCDAB' => 'Long CD AB',
            'LongBADC' => 'Long BA DC',
            'LongDCBA' => 'Long DC BA',

            'FloatABCD' => 'Float AB CD',
            'FloatCDAB' => 'Float CD AB',
            'FloatBADC' => 'Float BA DC',
            'FloatDCBA' => 'Float DC BA',

            'DoubleABCDEFGH' => 'Double AB CD EF GH',
            'DoubleGHEFCDAB' => 'Double GH EF CD AB',
            'DoubleBADCFEHG' => 'Double BA DC FE HG',
            'DoubleHGFEDCBA' => 'Double HG FE DC BA'
        ];
    }
}
