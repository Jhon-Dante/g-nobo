<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMethodAndPhoneColumnToBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->boolean('method')->after('number')->comment('1: Banco Nacional, 2: Zelle')->default(1);
            $table->string('phone')->after('identification')->comment('Para pago movil')->nullable();
            $table->string('email')->after('phone')->comment('para zelle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['method', 'phone', 'email']);
        });
    }
}
