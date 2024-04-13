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
        Schema::table('chat_members', function (Blueprint $table) {
            $table->foreignId('last_read_message_id')->after('admin')->nullable()->constrained('messages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_members', function (Blueprint $table) {
            $table->dropForeign(['last_read_message_id']);
            $table->dropColumn('last_read_message_id');
        });
    }
};
