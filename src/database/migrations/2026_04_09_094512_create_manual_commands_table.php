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
    Schema::create('manual_commands', function (Blueprint $table) {
        $table->id('cmd_id');
        $table->timestamp('timestamp');
        $table->string('command', 20);
        $table->string('source', 10);
        $table->foreignId('related_log_id')->nullable()->constrained('sensor_logs', 'log_id')->onDelete('set null');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_commands');
    }
};
