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
    Schema::create('sensor_logs', function (Blueprint $table) {
        $table->id('log_id');
        $table->timestamp('timestamp');
        $table->foreignId('config_id')->constrained('system_configs', 'config_id');
        $table->smallInteger('ldr1')->unsigned();
        $table->smallInteger('ldr2')->unsigned();
        $table->smallInteger('ldr3')->unsigned();
        $table->smallInteger('ldr4')->unsigned();
        $table->smallInteger('ldr5')->unsigned();
        $table->smallInteger('ldr6')->unsigned();
        $table->boolean('shadow_detected');
        $table->boolean('is_moving');
        $table->tinyInteger('servo_horizontal_angle');
        $table->tinyInteger('servo_vertical_angle');
        $table->float('ultrasonic_distance', 5,1)->nullable();
        $table->tinyInteger('ultrasonic_servo_angle')->nullable();
        $table->enum('mode', ['AUTO', 'MANUAL']);
        $table->boolean('emergency_stop_active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};
