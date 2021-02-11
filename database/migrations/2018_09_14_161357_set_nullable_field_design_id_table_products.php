<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNullableFieldDesignIdTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['design_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('design_id')->unsigned()->after('collection_id')->nullable();
            $table->foreign('design_id')->references('id')->on('designs');
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
            $table->dropColumn(['design_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('design_id')->unsigned()->after('collection_id');
            $table->foreign('design_id')->references('id')->on('designs');
        });
    }
}
