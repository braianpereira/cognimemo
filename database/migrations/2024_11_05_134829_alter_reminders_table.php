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
        Schema::table('reminders', function (Blueprint $table) {
            $table->unsignedBigInteger('reference_group_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn('reference_group_id');
            $table->dropColumn('starts_at');
            $table->dropColumn('ends_at');
            $table->dropColumn('reference');
        });
    }
};
