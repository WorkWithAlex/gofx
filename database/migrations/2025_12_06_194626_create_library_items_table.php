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
        Schema::create('library_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('type', 50)->nullable()->comment('pdf, link, cheat-sheet, video, etc.');
            $table->string('url', 1000)->nullable();
            $table->string('file_path', 1000)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->integer('file_size')->nullable()->comment('bytes');
            $table->string('summary', 800)->nullable();
            $table->boolean('public')->default(true);
            $table->timestamps();

            $table->index(['type', 'public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_items');
    }
};
