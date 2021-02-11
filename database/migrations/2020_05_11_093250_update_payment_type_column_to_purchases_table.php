<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaymentTypeColumnToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['payment_type']);
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('payment_type', ['1', '2','3', '4', '5'])->after('user_id')->comment('1: Transferencia, 2:  Pago Movil; 3: Zelle, 4: Paypal, 5: Efectivo');
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
            $table->dropColumn(['payment_type']);
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('payment_type', ['1', '2','3'])->after('user_id')->comment('1: MercadoPago; 2: Paypal; 3: Transferencia');
        });
    }
}
