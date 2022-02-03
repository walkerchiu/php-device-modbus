<?php

namespace WalkerChiu\DeviceModbus\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\DeviceModbus
 *
 * 
 */

class FunctionCode
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
     * @param Int  $code
     * @return Array|String
     */
    public static function getRelatedArea(?int $code): array
    {
        $items = [
            '1'  => 'Coils',
            '2'  => 'Discrete Inputs',
            '3'  => 'Holding Registers',
            '4'  => 'Input Registers',
            '5'  => 'Single Coil',
            '6'  => 'Single Register',
            '15' => 'Coils',
            '16' => 'Holding Registers',
            '23' => 'Holding Registers',
            '24' => ''
        ];

        return is_null($code) ? $items : $items[$code];
    }

    /**
     * @param Int  $code
     * @return Array|String
     */
    public static function getComment(?int $code): array
    {
        $items = [
            '1'  => 'Read up to 2000 contiguous memory bits',
            '2'  => 'Read up to 2000 contiguous input bits',
            '3'  => 'Read up to 125 contiguous memory words',
            '4'  => 'Read up to 125 contiguous input words',
            '5'  => 'Write one memory bit',
            '6'  => 'Write one memory word',
            '15' => 'Write up to 1968 contiguous memory bits',
            '16' => 'Write up to 123 contiguous memory words',
            '23' => 'Read up to 125 and write up 121 memory words',
            '24' => ''
        ];

        return is_null($code) ? $items : $items[$code];
    }

    /**
     * @param Int  $code
     * @return Array|String
     */
    public static function getActions(?int $code): array
    {
        $items = [
            '1'  => 'Read',
            '2'  => 'Read',
            '3'  => 'Read',
            '4'  => 'Read',
            '5'  => 'Write single',
            '6'  => 'Write single',
            '15' => 'Write multiple',
            '16' => 'Write multiple',
            '23' => '',
            '24' => ''
        ];

        return is_null($code) ? $items : $items[$code];
    }

    /**
     * @param Int  $code
     * @return Array|String
     */
    public static function all(?int $code = null): array
    {
        $items = [
            '1'  => 'Discrete Output Coils',
            '2'  => 'Discrete Input Contacts',
            '3'  => 'Analog Output Holding Registers',
            '4'  => 'Analog Input Registers',
            '5'  => 'Discrete Output Coil',
            '6'  => 'Analog Output Holding Register',
            '15' => 'Discrete Output Coils',
            '16' => 'Analog Output Holding Registers',
            '23' => 'Read/Write Multiple Registers',
            '24' => 'Read FIFO queue'
        ];

        return is_null($code) ? $items : $items[$code];
    }
}
