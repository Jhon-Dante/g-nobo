<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinMaxAndCostColumnToProductAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_amount', function (Blueprint $table) {
            $table->integer('min')->after('price');
            $table->integer('max')->after('min');
            $table->double('cost', 14, 2)->after('max');
            $table->integer('umbral')->after('cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_amount', function (Blueprint $table) {
            $table->dropColumn('min');
            $table->dropColumn('max');
            $table->dropColumn('cost');
            $table->dropColumn('umbral');
        });
    }
}
