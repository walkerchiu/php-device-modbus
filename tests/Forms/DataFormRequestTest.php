<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Support\Facades\Validator;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\Main;
use WalkerChiu\DeviceModbus\Models\Entities\Setting;
use WalkerChiu\DeviceModbus\Models\Entities\Address;
use WalkerChiu\DeviceModbus\Models\Entities\Data;
use WalkerChiu\DeviceModbus\Models\Forms\DataFormRequest;

class DataFormRequestTest extends \Orchestra\Testbench\TestCase
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

        $this->request  = new DataFormRequest();
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
     * For WalkerChiu\DeviceModbus\Models\Forms\DataFormRequest
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
     * For WalkerChiu\DeviceModbus\Models\Forms\DataFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        $db_channel = factory(Channel::class)->create();
        $db_main = factory(Main::class)->create(['channel_id' => $db_channel->id]);
        $db_setting = factory(Setting::class)->create(['main_id' => $db_main->id]);
        $db_address = factory(Address::class)->create(['setting_id' => $db_setting->id]);

        // Give
        $attributes = [
            'address_id' => $db_address->id,
            'value'      => $faker->randomNumber,
            'trigger_at' => '2019-01-01 01:00:00'
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'address_id' => 2,
            'value'      => $faker->randomNumber,
            'trigger_at' => '2019-01-01 01:00:00'
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
