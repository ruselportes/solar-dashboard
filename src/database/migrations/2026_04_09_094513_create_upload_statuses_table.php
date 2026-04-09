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
    Schema::create('upload_statuses', function (Blueprint $table) {
        $table->id('upload_id');
        $table->timestamp('timestamp');
        $table->integer('files_uploaded');
        $table->integer('files_pending');
        $table->integer('storage_used_kb');
        $table->boolean('upload_success');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_statuses');
    }
};
