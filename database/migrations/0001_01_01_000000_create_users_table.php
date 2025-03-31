<?php

use App\Models\User;
use App\Models\User\Position;
use App\Models\User\PositionCategory;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('phone_number')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('avatar')->nullable();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('position_name'); // Example: Ketua, Wakil, Anggota
            $table->unsignedBigInteger('sort')->default(1);
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('position_categories', function (Blueprint $table) {
            $table->id();
            $table->string('position_category_name'); // Example: Dewan Pengurus Pusat, Dewan Penasehat, Dewan Pengurus Cabang ...
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('position_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Position::class, 'position_id')->constrained()->cascadeOnDelete(); // Position (Ketua, Wakil Ketua, etc.)
            $table->foreignIdFor(PositionCategory::class, 'position_category_id')->constrained()->cascadeOnDelete(); // Category (DPP, DPC, etc.)
            $table->foreignId('parent_id')->nullable()->constrained('position_assignments')->nullOnDelete(); // Parent position recursive here guys 😱
            $table->timestamps();
        });

        Schema::create('positionnables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_assignment_id')->constrained()->onDelete('cascade');
            $table->morphs('positionnable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('position_categories');
        Schema::dropIfExists('position_assignments');
        Schema::dropIfExists('positionnables');
        Schema::dropIfExists('positions');
    }
};
