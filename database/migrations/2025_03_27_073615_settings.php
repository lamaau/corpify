<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->longText('value')->nullable();
                $table->string('settingable_type', 160)->nullable();
                $table->unsignedBigInteger('settingable_id')->nullable();
                $table->string('context')->nullable();
                $table->boolean('autoload')->default(0);
                $table->boolean('public')->default(1);
                $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->index(['settingable_type', 'settingable_id'], 'settingable_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
