<?php

use App\Constants\ConstStatus;
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
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('status', [
                ConstStatus::MESSAGE_SENT,
                ConstStatus::MESSAGE_SEEN,
                ConstStatus::MESSAGE_DELETED,
            ])->default(ConstStatus::MESSAGE_SENT)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
