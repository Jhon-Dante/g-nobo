<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FieldEnglish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_english')->nullable()->after('name');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('name_english')->nullable()->after('name');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->string('name_english')->nullable()->after('name');
        });

        Schema::table('designs', function (Blueprint $table) {
            $table->string('name_english')->nullable()->after('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_english')->nullable()->after('name');
            $table->string('description_english')->nullable()->after('description');
        });

        Schema::table('product_colors', function (Blueprint $table) {
            $table->string('name_english')->nullable()->after('name');
            $table->string('color_english')->nullable()->after('color');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title_english')->nullable()->after('title');
            $table->string('description_english')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_english');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('name_english');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('name_english');
        });

        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn('name_english');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name_english');
            $table->dropColumn('description_english');
        });

        Schema::table('product_colors', function (Blueprint $table) {
            $table->dropColumn('name_english');
            $table->dropColumn('color_english');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('title_english');
            $table->dropColumn('description_english');
        });
    }
}
