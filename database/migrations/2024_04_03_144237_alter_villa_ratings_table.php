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
        Schema::whenTableDoesntHaveColumn('villa_ratings', 'transaction_id', function (Blueprint $table) {
            $table->integer('transaction_id')->after('buyer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('villa_ratings', 'transaction_id', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
