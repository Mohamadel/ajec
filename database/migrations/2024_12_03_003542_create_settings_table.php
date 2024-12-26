<?php

// database/migrations/xxxx_xx_xx_create_settings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->unique();
            $table->string('value');
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key_name' => 'cost_per_part', 'value' => '1000'],
            ['key_name' => 'interest_rate', 'value' => '5'],
            ['key_name' => 'amende', 'value' => '500'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
