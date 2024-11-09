<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gajitenun', function (Blueprint $table) {
            $table->id();
            $table->string('minggu_ke');
            $table->integer('hari_1');
            $table->integer('hari_2');
            $table->integer('hari_3');
            $table->integer('hari_4');
            $table->integer('hari_5');
            $table->integer('hari_6');
            $table->integer('total_pengerjaan');
            $table->integer('gaji');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('update_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajitenun');
    }
};
