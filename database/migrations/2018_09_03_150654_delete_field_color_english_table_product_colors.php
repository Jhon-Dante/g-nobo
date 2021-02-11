<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFieldColorEnglishTableProductColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_colors', function (Blueprint $table) {
            $table->dropColumn('color_english');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_colors', function (Blueprint $table) {
            $table->string('color_english')->nullable()->after('color');
        });
    }
}
