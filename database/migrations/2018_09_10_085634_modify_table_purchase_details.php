<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTablePurchaseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->integer('quantity')->unsigned()->after('price');
            // $table->dropColumn('price_usd');
        });

        // Schema::table('purchases', function (Blueprint $table) {
        //     $table->integer('exchange_rate_id')->unsigned()->after('transaction');
        //     $table->foreign('exchange_rate_id')->references('id')->on('exchange_rates');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            // $table->double('price_usd', 10,2)->after('price');
            $table->dropColumn('quantity');
        });

        // Schema::table('purchases', function (Blueprint $table) {
        //     $table->dropForeign(['exchange_rate_id']);
        // });
    }
}
