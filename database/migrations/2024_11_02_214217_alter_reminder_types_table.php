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
        Schema::table('reminder_types', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->default(1);

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminder_types', function (Blueprint $table) {
            $table->dropForeign('reminder_types_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
};
