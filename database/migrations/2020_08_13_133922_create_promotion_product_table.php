<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_product', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promotion_id');
            $table->unsignedInteger('product_id');
            $table->integer('amount');
            $table->timestamps();
            $table->foreign('promotion_id')->references('id')->on('promotions');
            $table->foreign('product_id')->references('id')->on('product_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_product');
    }
}
