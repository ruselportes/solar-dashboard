<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->unsignedTinyInteger('servo_horizontal_angle')->change();
            $table->unsignedTinyInteger('servo_vertical_angle')->change();
            $table->unsignedTinyInteger('ultrasonic_servo_angle')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->tinyInteger('servo_horizontal_angle')->change();
            $table->tinyInteger('servo_vertical_angle')->change();
            $table->tinyInteger('ultrasonic_servo_angle')->nullable()->change();
        });
    }
};