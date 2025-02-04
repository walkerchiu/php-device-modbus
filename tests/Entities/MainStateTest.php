<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\Main;
use WalkerChiu\DeviceModbus\Models\Entities\MainState;
use WalkerChiu\DeviceModbus\Models\Entities\MainStateLang;

class MainStateTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on MainState.
     *
     * For WalkerChiu\DeviceModbus\Models\Entities\MainState
     * 
     * @return void
     */
    public function testMainState()
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
        $db_morph_1 = factory(MainState::class)->create(['main_id' => $db_main->id]);
        $db_morph_2 = factory(MainState::class)->create(['main_id' => $db_main->id]);
        $db_morph_3 = factory(MainState::class)->create(['main_id' => $db_main->id, 'is_enabled' => 1]);

        // Get records after creation
            // When
            $records = MainState::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = MainState::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            MainState::withTrashed()
                       ->find($db_morph_2->id)
                       ->restore();
            $record_2 = MainState::find($db_morph_2->id);
            $records = MainState::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, MainStateLang::class);

        // Scope query on enabled records
            // When
            $records = MainState::ofEnabled()
                                  ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = MainState::ofDisabled()
                                  ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
