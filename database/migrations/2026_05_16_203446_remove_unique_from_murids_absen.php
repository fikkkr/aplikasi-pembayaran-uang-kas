<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('murids', function (Blueprint $table) {
            // Kita drop index unique yang bikin error melulu tadi
            $table->dropUnique('murids_absen_unique');
        });
    }

    public function down()
    {
        Schema::table('murids', function (Blueprint $table) {
            $table->unique('absen');
        });
    }
};