<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateAndTurnColumnToPurchaseDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_deliveries', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->boolean('turn')->comment('1: Mañana, 2: Tarde, 3: Noche')->nullable();
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
            $table->dropColumn('date');
            $table->dropColumn('turn');
        });
    }
}
