<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Support\ServiceProvider;

class DeviceModbusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/device-modbus.php' => config_path('wk-device-modbus.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_device_modbus_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_device_modbus_table.php',
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-device-modbus');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-device-modbus'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-device-modbus.command.cleaner')
            ]);
        }

        config('wk-core.class.device-modbus.channel')::observe(config('wk-core.class.device-modbus.channelObserver'));
        config('wk-core.class.device-modbus.channelLang')::observe(config('wk-core.class.device-modbus.channelLangObserver'));

        config('wk-core.class.device-modbus.main')::observe(config('wk-core.class.device-modbus.mainObserver'));
        config('wk-core.class.device-modbus.mainLang')::observe(config('wk-core.class.device-modbus.mainLangObserver'));
        config('wk-core.class.device-modbus.mainState')::observe(config('wk-core.class.device-modbus.mainStateObserver'));
        config('wk-core.class.device-modbus.mainStateLang')::observe(config('wk-core.class.device-modbus.mainStateLangObserver'));

        config('wk-core.class.device-modbus.setting')::observe(config('wk-core.class.device-modbus.settingObserver'));
        config('wk-core.class.device-modbus.settingLang')::observe(config('wk-core.class.device-modbus.settingLangObserver'));
        config('wk-core.class.device-modbus.settingState')::observe(config('wk-core.class.device-modbus.settingStateObserver'));
        config('wk-core.class.device-modbus.settingStateLang')::observe(config('wk-core.class.device-modbus.settingStateLangObserver'));

        config('wk-core.class.device-modbus.address')::observe(config('wk-core.class.device-modbus.addressObserver'));
        config('wk-core.class.device-modbus.addressLang')::observe(config('wk-core.class.device-modbus.addressLangObserver'));

        config('wk-core.class.device-modbus.data')::observe(config('wk-core.class.device-modbus.dataObserver'));
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-device-modbus')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/device-modbus.php', 'wk-device-modbus'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/device-modbus.php', 'device-modbus'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
