<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUtilitiesColumnsToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->double('utilidad_bruta', 16, 2)
                ->nullable()
                ->comment('Utilidad sin descuentos ni ofertas. En $')
                ->after('total');
            $table->double('utilidad', 16, 2)
                ->nullable()
                ->comment('Utilidad con descuentos. En $')
                ->after('utilidad_bruta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['utilidad_bruta', 'utilidad']);
        });
    }
}
