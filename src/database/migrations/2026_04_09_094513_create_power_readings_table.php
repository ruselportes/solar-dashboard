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
    Schema::create('power_readings', function (Blueprint $table) {
        $table->id('power_id');
        $table->timestamp('timestamp');
        $table->float('battery_voltage', 5,2);
        $table->float('panel_voltage', 5,2)->nullable();
        $table->foreignId('log_id')->nullable()->constrained('sensor_logs', 'log_id')->onDelete('set null');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_readings');
    }
};
