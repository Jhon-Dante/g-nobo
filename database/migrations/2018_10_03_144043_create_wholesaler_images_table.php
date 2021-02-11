<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholesalerImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesaler_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file');
            $table->integer('wholesaler_id')->unsigned();
            $table->foreign('wholesaler_id')->references('id')->on('wholesalers')->onDelete('cascade');
            $table->enum('main', ['0', '1'])->default('0')->comment('0: Secundarias; 1: Principal');
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
        Schema::dropIfExists('wholesaler_images');
    }
}
