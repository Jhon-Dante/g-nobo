<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressNullablePurchaseDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_deliveries', function (Blueprint $table) {
            $table->string('address')->comment('direccion')->nullable()->change();
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
            $table->string('address')->comment('direccion')->change();
        });
    }
}
