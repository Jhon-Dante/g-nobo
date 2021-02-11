<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('municipality_id')->unsigned()->nullable();
            $table->integer('parish_id')->unsigned()->nullable();
            $table->integer('charge_on_delivery')->comment('Cobro al destino');
            $table->integer('send_to_store')->comment('Envio a la tienda');
            $table->text('address')->comment('Direccion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_deliveries');
    }
}
