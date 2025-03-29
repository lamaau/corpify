<?php

use App\Models\User;
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

        Schema::create('articles', function (Blueprint $table) {
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
            $table->string('status')->default('featured');
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_features');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('work_programs');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('news');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('contents');
    }
};
