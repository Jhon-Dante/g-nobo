<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCatalogueToTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('catalogue', ['1', '2', '3'])->comment('1: Dama; 2: Caballeros; 3: Niños')->after('price_2');
            $table->enum('coin', ['1', '2'])->comment('1: BS; 2: USD;')->after('description_english');
            $table->dropColumn('price_usd_1');
            $table->dropColumn('price_usd_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('catalogue');
            $table->dropColumn('coin');
            $table->double('price_usd_1', 10,2)->nullable();
            $table->double('price_usd_2', 10,2)->nullable();
        });
    }
}
