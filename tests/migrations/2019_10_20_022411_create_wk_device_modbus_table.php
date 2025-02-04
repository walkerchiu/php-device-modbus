<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkDeviceModbusTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.device-modbus.channels'), function (Blueprint $table) {
            $table->uuid('id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->string('protocol');
            $table->string('interface');
            $table->unsignedSmallInteger('scan_interval')->default(500);
            $table->unsignedSmallInteger('polling_timeout')->default(3000);
            $table->unsignedSmallInteger('retry_interval')->default(1000);

            $table->unsignedInteger('baud')->default(115200);
            $table->string('parity', 5)->default("None");
            $table->unsignedSmallInteger('stop_bit')->default(1);

            $table->string('ip');
            $table->unsignedSmallInteger('port');

            $table->timestampsTz();
            $table->softDeletes();

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
            $table->index('protocol');
            $table->index('interface');
            $table->index(['ip', 'port']);
        });
        if (!config('wk-device-modbus.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-modbus.channels_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-modbus.main'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('channel_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->unsignedBigInteger('order')->nullable();
            $table->unsignedSmallInteger('slave_id');
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('channel_id')->references('id')
                  ->on(config('wk-core.table.device-modbus.channels'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('slave_id');
            $table->index('is_enabled');
        });
        if (!config('wk-device-modbus.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-modbus.main_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-modbus.main_states'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('main_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->string('mean');
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('main_id')->references('id')
                  ->on(config('wk-core.table.device-modbus.main'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
        });
        if (!config('wk-device-modbus.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-modbus.main_states_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-modbus.settings'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('main_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->char('typology', 2);
            $table->char('function_code', 2);
            $table->string('format');
            $table->unsignedInteger('data_start_address');
            $table->unsignedInteger('data_count');
            $table->float('scale_ratio')->nullable();

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('main_id')->references('id')
                  ->on(config('wk-core.table.device-modbus.main'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
            $table->index(['main_id', 'typology']);
            $table->index(['main_id', 'function_code']);
        });
        if (!config('wk-device-modbus.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-modbus.settings_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-modbus.settings_states'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('setting_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->string('mean');
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('setting_id')->references('id')
                  ->on(config('wk-core.table.device-modbus.settings'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
        });
        if (!config('wk-device-modbus.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-modbus.settings_states_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }

        Schema::create(config('wk-core.table.device-modbus.addresses'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('setting_id');
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->unsignedBigInteger('order')->nullable();
            $table->string('data_type');
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('setting_id')->references('id')
                  ->on(config('wk-core.table.device-modbus.settings'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->primary('id');
            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
        });
        if (!config('wk-device-modbus.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.device-modbus.addresses_lang'), function (Blueprint $table) {
                $table->uuid('id');
                $table->uuidMorphs('morph');
                $table->uuid('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->primary('id');
            });
        }


        Schema::create(config('wk-core.table.device-modbus.data'), function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('address_id');
            $table->float('value');
            $table->timestamp('trigger_at');

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('address_id')->references('id')
                  ->on(config('wk-core.table.device-modbus.addresses'))
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->primary('id');
        });
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.device-modbus.data'));

        Schema::dropIfExists(config('wk-core.table.device-modbus.addresses_lang'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.addresses'));

        Schema::dropIfExists(config('wk-core.table.device-modbus.settings_states_lang'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.settings_states'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.settings_lang'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.settings'));

        Schema::dropIfExists(config('wk-core.table.device-modbus.main_states_lang'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.main_states'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.main_lang'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.main'));

        Schema::dropIfExists(config('wk-core.table.device-modbus.channels_lang'));
        Schema::dropIfExists(config('wk-core.table.device-modbus.channels'));
    }
}
