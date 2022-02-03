<?php

namespace WalkerChiu\DeviceModbus\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\DeviceModbus
 *
 *
 */

class Typology
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
     * @param Bool  $onlyVaild
     * @return Array
     */
    public static function options($onlyVaild = false): array
    {
        $items = $onlyVaild ? [] : ['' => trans('php-core::system.null')];

        $types = self::all();
        foreach ($types as $key => $value) {
            $items = array_merge($items, [$key => trans('php-device-modbus::constants.typology.'.$key)]);
        }

        return $items;
    }

    /**
     * @param String  $code
     * @return Array
     */
    public static function getObjectNumber(string $code): array
    {
        switch ($code) {
            case '0x':
                return ['min' => 1, 'max' => 9999];
            case '1x':
                return ['min' => 10001, 'max' => 19999];
            case '3x':
                return ['min' => 30001, 'max' => 39999];
            case '4x':
                return ['min' => 40001, 'max' => 49999];
        }
    }

    /**
     * @return Array
     */
    public static function getPDUAddress(): array
    {
        return ['begin' => 0, 'end' => 9998];
    }

    /**
     * @param String  $code
     * @return String
     */
    public static function getObjectType(string $code): string
    {
        switch ($code) {
            case '0x':
            case '1x':
                return 'Single bit';

            case '3x':
            case '4x':
                return '16-bit word';
        }
    }

    /**
     * @param String  $code
     * @return String
     */
    public static function getAccessType(string $code): string
    {
        switch ($code) {
            case '0x':
                return 'Read-Write';
            case '1x':
                return 'Read-Only';
            case '3x':
                return 'Read-Only';
            case '4x':
                return 'Read-Write';
        }
    }

    /**
     * @param String  $code
     * @return String
     */
    public static function getComment(string $code): string
    {
        switch ($code) {
            case '0x':
                return 'Can be alterable by Master';
            case '1x':
                return 'Provided by an I/O Slave';
            case '3x':
                return 'Provided by an I/O Slave';
            case '4x':
                return 'Can be alterable by Master';
        }
    }

    /**
     * @param String  $code
     * @return Array|String
     */
    public static function getFunctionCode(?string $code): array
    {
        $items = [
            '0x' => ['1', '5', '15'],
            '1x' => ['2'],
            '3x' => ['4'],
            '4x' => ['3', '6', '16']
        ];

        return is_null($code) ? $items : $items[$code];
    }

    /**
     * @return Array
     */
    public static function all(): array
    {
        return [
            '0x' => 'Coils',
            '1x' => 'Discrete Inputs',
            '3x' => 'Input Registers',
            '4x' => 'Holding Registers'
        ];
    }
}
