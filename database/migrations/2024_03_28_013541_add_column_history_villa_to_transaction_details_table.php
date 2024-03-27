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

        Schema::whenTableDoesntHaveColumn('transaction_details', 'villa_price', function (Blueprint $table) {
            $table->unsignedInteger('villa_price')->after('end')->nullable()->default(0);
        });

        Schema::whenTableDoesntHaveColumn('transaction_details', 'villa_address', function (Blueprint $table) {
            $table->text('villa_address')->after('end')->nullable();
        });

        Schema::whenTableDoesntHaveColumn('transaction_details', 'villa_name', function (Blueprint $table) {
            $table->string('villa_name')->after('end')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::whenTableHasColumn('transaction_details', 'villa_price', function (Blueprint $table) {
            $table->dropColumn('villa_price');
        });

        Schema::whenTableHasColumn('transaction_details', 'villa_address', function (Blueprint $table) {
            $table->dropColumn('villa_address');
        });

        Schema::whenTableHasColumn('transaction_details', 'villa_name', function (Blueprint $table) {
            $table->dropColumn('villa_name');
        });
    }
};
