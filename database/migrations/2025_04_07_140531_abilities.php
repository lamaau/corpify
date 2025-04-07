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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->tinyText('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('role_has_abilities', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ability_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'ability_id']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->morphs('model'); // model_type & model_id
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('model_has_abilities', function (Blueprint $table) {
            $table->foreignId('ability_id')->constrained()->cascadeOnDelete();
            $table->morphs('model'); // model_type & model_id
            $table->primary(['ability_id', 'model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_abilities');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_abilities');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('roles');
    }
};
