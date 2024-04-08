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
        Schema::whenTableDoesntHaveColumn('villas', 'promote', function (Blueprint $table) {
            $table->boolean('promote')->default(false)->after('bypass_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('villas', 'promote', function (Blueprint $table) {
            $table->dropColumn('promote');
        });
    }
};
