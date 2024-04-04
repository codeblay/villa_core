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
        Schema::whenTableDoesntHaveColumn('villas', 'bypass_rating', function (Blueprint $table) {
            $table->tinyInteger('bypass_rating')->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('villas', 'bypass_rating', function (Blueprint $table) {
            $table->dropColumn('bypass_rating');
        });
    }
};
