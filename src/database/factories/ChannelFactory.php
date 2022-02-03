<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\ChannelLang;

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
        'order'      => $faker->randomNumber,
        'protocol'   => 'TCP',
        'interface'  => 'ip',
        'ip'         => $faker->ipv4,
        'port'       => $faker->numberBetween($min = 1, $max = 65535)
    ];
});

$factory->define(ChannelLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
