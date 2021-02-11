<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchaseDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_deliveries', function (Blueprint $table) {
            $table->dropColumn(['charge_on_delivery', 'send_to_store']);
            $table->integer('type')->after('parish_id')->comment('1: Cobro al destino, 2: Envio a tienda')->nullable();
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
            $table->dropColumn(['type']);
            $table->integer('charge_on_delivery')->after('parish_id')->comment('Cobro al destino');
            $table->integer('send_to_store')->after('charge_on_delivery')->comment('Envio a la tienda');
        });
    }
}
