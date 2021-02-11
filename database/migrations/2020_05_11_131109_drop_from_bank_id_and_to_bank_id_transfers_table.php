<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFromBankIdAndToBankIdTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['from_bank_id']);
            $table->dropForeign(['to_bank_id']);
            $table->dropColumn(['from_bank_id', 'to_bank_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->integer('from_bank_id')->unsigned()->after('id');
            $table->integer('to_bank_id')->unsigned()->after('from_bank_id');
            $table->foreign('to_bank_id')->references('id')->on('banks');
            $table->foreign('from_bank_id')->references('id')->on('banks');
        });
    }
}
