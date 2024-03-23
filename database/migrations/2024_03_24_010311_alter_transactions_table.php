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
        Schema::whenTableDoesntHaveColumn('transactions', 'bank_id', function (Blueprint $table) {
            $table->integer('bank_id')->after('buyer_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('external_id')->nullable()->change();
            $table->json('external_response')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('transactions', 'bank_id', function (Blueprint $table) {
            $table->dropColumn('bank_id');
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('external_id')->change();
            $table->json('external_response')->change();
        });
    }
};
