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
        Schema::dropIfExists('chat_actions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('chat_actions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->text('text');
            $table->timestamps();
        });
    }
};
