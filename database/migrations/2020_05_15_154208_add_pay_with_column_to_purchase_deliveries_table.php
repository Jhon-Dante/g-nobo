<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayWithColumnToPurchaseDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_deliveries', function (Blueprint $table) {
            $table->double('pay_with')
                ->after('parish_id')
                ->nullable()
                ->comment('Monto con el que paga el cliente en efectivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_deliveries', function (Blueprint $table) {
            $table->dropColumn('pay_with');
        });
    }
}
