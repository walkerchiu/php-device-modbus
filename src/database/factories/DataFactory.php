<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\DeviceModbus\Models\Entities\Data;

$factory->define(Data::class, function (Faker $faker) {
    return [
        'value'      => $faker->randomNumber,
        'trigger_at' => '2019-01-01 01:00:00'
    ];
});
