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
        Schema::whenTableDoesntHaveColumn('buyers', 'fcm_token', function (Blueprint $table) {
            $table->string('fcm_token')->after('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('buyers', 'fcm_token', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
    }
};
