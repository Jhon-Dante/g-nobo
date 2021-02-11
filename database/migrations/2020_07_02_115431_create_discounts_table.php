<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->default(true)->comment('1: Activo: 0: Inactivo');
            $table->string('type');
            $table->integer('limit')
                ->nullable()
                ->comment('La cantidad de veces que un usuario puede usar este descuento');
            $table->string('name');
            $table->double('percentage', 5, 2);
            $table->date('start');
            $table->date('end');
            $table->integer('quantity_product')
                ->nullable()
                ->comment('Limite de productos (Aplica para quantity_product)');
            $table->double('minimum_purchase', 10, 2)
                ->nullable()
                ->comment('Monto mínimo en $ para aplicar descuento (Aplica para minimum_purchase)');
            $table->integer('quantity_purchase')
                ->nullable()
                ->comment('Cantidad de compras necesarias para aplicar descuento (Aplica para quantity_purchase)');
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
        Schema::dropIfExists('discounts');
    }
}
