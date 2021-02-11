<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplenishmentProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replenishments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('product_presentation')->comment('Presentacion del producto para su monto, ya posee id de relacion a producto original');
            $table->integer('type')->comment('0: Entrada, 1: Salida')->default(0);
            $table->integer('existing')->comment('Cantidad existente');
            $table->integer('modified')->comment('Cantidad por la que se realiza la operacion de reposicion de inventario');
            $table->integer('final')->comment('Cantidad final (existing - modified)');
            $table->text('reason');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replenishments');
    }
}
