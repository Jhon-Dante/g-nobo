<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToTextFieldsDescriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('description_english');
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->text('description_english')->nullable()->after('description');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('description_english');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->text('description_english')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
