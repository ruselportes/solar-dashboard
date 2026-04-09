<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('system_configs', function (Blueprint $table) {
        $table->id('config_id');
        $table->integer('shadow_threshold')->default(300);
        $table->unsignedTinyInteger('servo_home_horizontal')->default(90);
        $table->unsignedTinyInteger('servo_home_vertical')->default(90);
        $table->unsignedTinyInteger('ultrasonic_scan_min_angle')->default(0);
        $table->unsignedTinyInteger('ultrasonic_scan_max_angle')->default(180);
        $table->float('obstacle_distance_threshold', 5,1)->default(50.0);
        $table->unsignedTinyInteger('motor_speed')->default(200);
        $table->integer('upload_interval_sec')->default(30);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};
