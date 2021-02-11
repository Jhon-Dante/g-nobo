<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_bank_id')->unsigned();
            $table->integer('to_bank_id')->unsigned();
            $table->string('number');
            $table->integer('user_id')->unsigned();
            $table->double('amount',10,2);
            $table->foreign('to_bank_id')->references('id')->on('banks');
            $table->foreign('from_bank_id')->references('id')->on('banks');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('transfers');
    }
}
