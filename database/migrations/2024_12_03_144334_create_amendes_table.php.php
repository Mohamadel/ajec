<?php

// database/migrations/xxxx_xx_xx_create_amendes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmendesTable extends Migration
{
    public function up()
    {
        Schema::create('amendes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('amount');
            $table->string('reason');
            $table->string('status');
            $table->date('date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('amendes');
    }
}
