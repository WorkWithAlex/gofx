<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('level', 100)->index();
            $table->string('message', 1000)->nullable();
            $table->longText('description')->nullable();
            $table->longText('context')->nullable(); // JSON string (masked)
            $table->string('file', 1000)->nullable();
            $table->integer('line')->nullable();
            $table->longText('stack')->nullable();
            $table->string('logged_by', 150)->default('system')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('url', 2048)->nullable();
            $table->string('method', 10)->nullable();
            $table->longText('headers')->nullable(); // JSON string
            $table->longText('payload')->nullable(); // JSON string (masked)
            $table->string('host', 255)->nullable();
            $table->string('env', 50)->nullable()->index();
            $table->timestamps();

            // add indexes to help queries
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
