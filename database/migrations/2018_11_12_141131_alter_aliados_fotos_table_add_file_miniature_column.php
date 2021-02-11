<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAliadosFotosTableAddFileMiniatureColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aliados_fotos', function (Blueprint $table) {
            $table->string('file_miniature', 200)->after('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aliados_fotos', function (Blueprint $table) {
            //
        });
    }
}
