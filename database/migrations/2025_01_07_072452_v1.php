<?php

use App\Enums\Gallery\GalleryStatus;
use App\Models\Member\MemberCategory;
use App\Models\Member\MemberPosition;
use App\Models\User;
use App\Models\User\Profile;
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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->longText('question');
            $table->longText('answer');
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->morphs('taggable');
            $table->timestamps();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('summary')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('program_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('icon');
            $table->string('feature_name');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('work_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->tinyText('caption')->nullable();
            $table->string('status')->default(GalleryStatus::Featured());
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('member_positions', function (Blueprint $table) {
            $table->id();
            $table->string('member_position_name'); // Example: Ketua, Wakil, Anggota
            $table->unsignedBigInteger('sort')->default(1);
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('member_categories', function (Blueprint $table) {
            $table->id();
            $table->string('member_category_name'); // Example: Pengurus Pusat, Dewan Penasehat ...
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MemberPosition::class, 'member_position_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(MemberCategory::class, 'member_category_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Profile::class, 'profile_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulations');
        Schema::dropIfExists('member_positions');
        Schema::dropIfExists('member_categories');
        Schema::dropIfExists('members');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('program_features');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('work_programs');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('news');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('contents');
    }
};
