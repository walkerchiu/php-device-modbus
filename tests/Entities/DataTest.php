<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\Main;
use WalkerChiu\DeviceModbus\Models\Entities\Setting;
use WalkerChiu\DeviceModbus\Models\Entities\Address;
use WalkerChiu\DeviceModbus\Models\Entities\Data;

class DataTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
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
     * A basic functional test on Data.
     *
     * For WalkerChiu\DeviceModbus\Models\Entities\Data
     * 
     * @return void
     */
    public function testData()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-device-modbus.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-device-modbus.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-device-modbus.soft_delete', 1);

        // Give
        $db_channel = factory(Channel::class)->create();
        $db_main = factory(Main::class)->create(['channel_id' => $db_channel->id]);
        $db_setting = factory(Setting::class)->create(['main_id' => $db_main->id]);
        $db_address = factory(Address::class)->create(['setting_id' => $db_setting->id]);
        $db_data_1 = factory(Data::class)->create(['address_id' => $db_address->id]);
        $db_data_2 = factory(Data::class)->create(['address_id' => $db_address->id]);
        $db_data_3 = factory(Data::class)->create(['address_id' => $db_address->id]);

        // Get records after creation
            // When
            $records = Data::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_data_2->delete();
            $records = Data::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Data::withTrashed()
                ->find($db_data_2->id)
                ->restore();
            $record_2 = Data::find($db_data_2->id);
            $records = Data::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);
    }
}
