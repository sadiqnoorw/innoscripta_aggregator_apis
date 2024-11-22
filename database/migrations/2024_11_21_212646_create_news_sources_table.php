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
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // Index on slug
            $table->string('name')->index();
            $table->text('description');
            $table->string('url');
            $table->string('category')->nullable()->index(); // Index on category
            $table->string('language')->nullable()->index(); // Index on language
            $table->string('country')->nullable(); 
            $table->string('api_source')->nullable(); // Optional: Index if needed
            $table->timestamp('fetched_at')->nullable(); // Nullable for fetched_at field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
