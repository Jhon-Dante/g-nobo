<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('image')->nullable()->default(null);
            $table->integer('limit')->comment('-1: Infinito');
            $table->double('discount_percentage', 10, 3)->nullable()->default(null);
            $table->date('start_date')->comment('Fecha de inicio');
            $table->date('end_date')->comment('Fecha de finalizado');
            $table->integer('status')->default(1)->comment('0: Inactiva, 1: Activa');
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
        Schema::dropIfExists('promotions');
    }
}
