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
        Schema::create('villa_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("villa_id")->nullable();
            $table->string("name");
            $table->integer("total_unit")->default(0);
            $table->integer("price")->default(0);
            $table->text("description");
            $table->boolean("is_publish")->default(false);
            $table->timestamps();
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('villa_id', 'villa_type_id');
        });
        
        Schema::table('villa_schedules', function (Blueprint $table) {
            $table->renameColumn('villa_id', 'villa_type_id');
            $table->rename('villa_type_schedules');
        });

        Schema::create('villa_type_ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('villa_type_id');
            $table->integer('buyer_id');
            $table->tinyInteger('rating')->default(0);
            $table->timestamps();
        });

        Schema::create('villa_type_facilities', function (Blueprint $table) {
            $table->id();
            $table->integer('villa_type_id');
            $table->integer('facility_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villa_types');
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('villa_type_id', 'villa_id');
        });
        
        Schema::table('villa_type_schedules', function (Blueprint $table) {
            $table->renameColumn('villa_type_id', 'villa_id');
            $table->rename('villa_schedules');
        });

        Schema::dropIfExists('villa_type_ratings');
        
        Schema::dropIfExists('villa_type_facilities');
    }
};
