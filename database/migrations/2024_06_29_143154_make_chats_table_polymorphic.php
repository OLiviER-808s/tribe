<?php

use App\Constants\ConstEnvironments;
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
        if (config('app.env') === ConstEnvironments::TESTING) {
            Schema::dropIfExists('chats');

            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->text('name');
                $table->string('attachable_type')->nullable();
                $table->unsignedBigInteger('attachable_id')->nullable();
                $table->foreignId('created_by_id')->constrained('users');
                $table->timestamps();
            });
        } else {
            Schema::table('chats', function (Blueprint $table) {
                $table->dropForeign(['conversation_id']);
                $table->dropColumn('conversation_id');

                $table->string('attachable_type')->after('name')->nullable();
                $table->unsignedBigInteger('attachable_id')->after('attachable_type')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('app.env') === ConstEnvironments::TESTING) {
            Schema::dropIfExists('chats');

            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->text('name');
                $table->foreignId('conversation_id')->nullable()->constrained('conversations');
                $table->foreignId('created_by_id')->constrained('users');
                $table->timestamps();
            });
        } else {
            Schema::table('chats', function ($table) {
                $table->dropColumn('attachable_type');
                $table->dropColumn('attachable_id');

                $table->foreignId('conversation_id')->nullable()->constrained('conversations');
            });
        }
    }
};
