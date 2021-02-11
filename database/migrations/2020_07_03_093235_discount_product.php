<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiscountProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_product', function (Blueprint $table) {
            $table->unsignedInteger('discount_id');
            $table->unsignedInteger('product_id');

            $table->primary(['discount_id', 'product_id']);
            $table->foreign('discount_id')->references('id')->on('discounts');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_product');
    }
}
