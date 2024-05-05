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
        Schema::create('topic_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->char('emoji')->nullable();
            $table->string('label')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('topic_categories');
            $table->foreignId('parent_id')->nullable()->constrained('topics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
        Schema::dropIfExists('topic_categories');
    }
};
