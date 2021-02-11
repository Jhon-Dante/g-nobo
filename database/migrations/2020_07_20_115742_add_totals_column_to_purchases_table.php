<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalsColumnToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->double('subtotal_bruto', 16, 2)
                ->nullable()
                ->after('transfer_id')
                ->comment('Subtotal sin descuentos ni ofertas. En $');
            $table->double('subtotal', 16, 2)
                ->nullable()
                ->after('subtotal_bruto')
                ->comment('Subtotal final. En $');
            $table->double('total', 16, 2)
                ->nullable()
                ->after('subtotal')
                ->comment('subtotal + shipping_fee. En $');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['subtotal_bruto', 'subtotal', 'total']);
        });
    }
}
