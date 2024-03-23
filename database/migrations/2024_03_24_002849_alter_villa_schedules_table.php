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
        Schema::whenTableDoesntHaveColumn('villa_schedules', 'transaction_id', function (Blueprint $table) {
            $table->integer('transaction_id')->after('villa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('villa_schedules', 'transaction_id', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
