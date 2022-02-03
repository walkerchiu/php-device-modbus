<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\DeviceModbus\Models\Entities\Setting;
use WalkerChiu\DeviceModbus\Models\Entities\SettingLang;

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'serial'             => $faker->isbn10,
        'identifier'         => $faker->slug,
        'order'              => $faker->randomNumber,
        'typology'           => $faker->randomElement(config('wk-core.class.device-modbus.typology')::getCodes()),
        'function_code'      => $faker->randomElement(config('wk-core.class.device-modbus.functionCode')::getCodes()),
        'format'             => $faker->randomElement(config('wk-core.class.device-modbus.format')::getCodes()),
        'data_start_address' => 0,
        'data_count'         => 2
    ];
});

$factory->define(SettingLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
