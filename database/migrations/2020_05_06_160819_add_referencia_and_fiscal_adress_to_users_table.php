<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenciaAndFiscalAdressToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referencia')->after('direccion')->comment('Punto de referencia de la dirección')->nullable();
            $table->string('fiscal')->comment('Dirección fiscal si es empresa')->after('empresa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referencia');
            $table->dropColumn('fiscal');
        });
    }
}
