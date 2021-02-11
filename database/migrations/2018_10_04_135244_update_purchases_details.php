<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchasesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->unsignedInteger('wholesalers_id')->nullable();
            $table->foreign('wholesalers_id')->references('id')->on('wholesalers');

            $table->unsignedInteger('product_amount_id')->nullable()->change();
            $table->foreign('product_amount_id')->references('id')->on('product_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn(['wholesalers_id']);
        });
    }
}
