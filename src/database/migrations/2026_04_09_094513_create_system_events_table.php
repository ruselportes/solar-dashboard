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
    Schema::create('system_events', function (Blueprint $table) {
        $table->id('event_id');
        $table->timestamp('timestamp');
        $table->string('event_type', 30);
        $table->text('details')->nullable();
        $table->foreignId('trigger_log_id')->nullable()->constrained('sensor_logs', 'log_id')->onDelete('set null');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_events');
    }
};
