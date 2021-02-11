<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholesalersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesalers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('quantity');
            $table->double('price', [12,2]); 
            $table->enum('coin', ['1', '2'])->comment('1: BS, 2: USD');
            $table->text('description');
            $table->string('name_english');
            $table->text('description_english');
            $table->unsignedInteger('filter_id');
            $table->foreign('filter_id')->references('id')->on('filters');
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
        Schema::dropIfExists('wholesalers');
    }
}
