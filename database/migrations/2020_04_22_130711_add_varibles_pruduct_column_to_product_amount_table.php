<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariblesPruductColumnToProductAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_amount', function (Blueprint $table) {
            $table->integer('unit')
                ->comment('1: Gr, 2: Kg, 3: Ml, 4: L. 5: Cm')
                ->after('amount');
            $table->double('presentation', 5, 2)
                ->default(0)
                ->comment('Este valor es solo para productos variables')
                ->after('unit');
            $table->double('price', [12, 2])
                ->default(0)
                ->comment('Si es 0 es debido a que es un producto simple')
                ->after('presentation');
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
            $table->dropColumn('unit');
            $table->dropColumn('presentation');
            $table->dropColumn('price');
        });
    }
}
