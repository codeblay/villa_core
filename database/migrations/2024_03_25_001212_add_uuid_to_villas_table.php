<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::whenTableDoesntHaveColumn('villas', 'uuid', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        $villas = DB::table('villas')->select(['id'])->get();
        foreach ($villas as $villa) {
            $uuid = Uuid::uuid4();
            DB::table('villas')->where('id', $villa->id)->update(['uuid' => $uuid]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::whenTableHasColumn('villas', 'uuid', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
