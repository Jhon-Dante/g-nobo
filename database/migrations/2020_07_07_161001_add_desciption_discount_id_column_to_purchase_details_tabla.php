<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDesciptionDiscountIdColumnToPurchaseDetailsTabla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->string('offer_description')->after('coin')->nullable()->comment('Si le aplica una oferta');
            $table->string('discount_description')->after('offer_description')->nullable()->comment('Si le aplico descuento');
            $table->unsignedInteger('discount_id')->after('product_amount_id')->nullable();
            $table->unsignedInteger('offer_id')->after('discount_id')->nullable();
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
            $table->dropColumn('offer_description');
            $table->dropColumn('discount_description');
            $table->dropColumn('discount_id');
            $table->dropColumn('offer_id');
        });
    }
}
