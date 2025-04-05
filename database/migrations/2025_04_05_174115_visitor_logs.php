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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45); // IPv6 compatible
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('device')->nullable(); // Mobile/Desktop/Tablet/etc.
            $table->string('platform')->nullable(); // Windows/Linux/MacOS
            $table->string('browser')->nullable(); // Chrome, Safari, etc.

            $table->boolean('is_bot')->default(false);
            $table->unsignedInteger('visit_count')->default(1); // Number of visits by this IP
            $table->string('referrer')->nullable(); // From header
            $table->string('url')->nullable(); // The page visited

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
