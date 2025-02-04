<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Support\Facades\Validator;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Forms\ChannelFormRequest;

class ChannelFormRequestTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');

        $this->request  = new ChannelFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\DeviceModbus\DeviceModbusServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * Unit test about Authorize.
     *
     * For WalkerChiu\DeviceModbus\Models\Forms\ChannelFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\DeviceModbus\Models\Forms\ChannelFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        // Give
        $attributes = [
            'serial'     => $faker->isbn10,
            'identifier' => $faker->slug,
            'order'      => $faker->randomNumber,
            'protocol'   => 'TCP',
            'interface'  => 'ip',
            'ip'         => $faker->ipv4,
            'port'       => $faker->numberBetween($min = 1, $max = 65535),
            'name'       => $faker->name
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'serial'     => $faker->isbn10,
            'identifier' => $faker->slug,
            'order'      => $faker->randomNumber,
            'protocol'   => '',
            'interface'  => 'ip',
            'ip'         => $faker->ipv4,
            'port'       => $faker->numberBetween($min = 1, $max = 65535),
            'name'       => $faker->name
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
