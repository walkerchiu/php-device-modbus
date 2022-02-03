<?php

namespace WalkerChiu\DeviceModbus\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\DeviceModbus
 *
 *
 */

class ProtocolType
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
            $items = array_merge($items, [$key => trans('php-device-modbus::constants.protocol.'.$key)]);
        }

        return $items;
    }

    /**
     * @return Array
     */
    public static function all(): array
    {
        return [
            'ASCII' => 'ASCII',
            'RTU'   => 'RTU',
            'TCP'   => 'TCP'
        ];
    }
}
