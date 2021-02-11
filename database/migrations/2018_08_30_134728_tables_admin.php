<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablesAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0: Inactivo; 1: Activo; 2: Eliminado;');
            $table->timestamps();
        });
        
        Schema::create('subcategories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->integer('category_id')->unsigned();
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0: Inactivo; 1: Activo; 2: Eliminado;');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
        });        
        
        Schema::create('sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->enum('status', ['0', '1', '2']);
        });
        
        Schema::create('category_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('size_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('size_id')->references('id')->on('sizes');
        });
        
        
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0: Inactivo; 1: Activo; 2: Eliminado;');
            $table->timestamps();
        });
        
        Schema::create('designs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0: Inactivo; 1: Activo; 2: Eliminado;');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('price_1', 10,2);
            $table->double('price_2', 10,2);
            $table->integer('category_id')->unsigned();
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->integer('collection_id')->unsigned();
            $table->integer('design_id')->unsigned();
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0: Inactivo; 1: Activo; 2: Eliminado;');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->foreign('collection_id')->references('id')->on('collections');
            $table->foreign('design_id')->references('id')->on('designs');
        });

        Schema::create('product_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('color', 20)->nullable();
            $table->integer('product_id')->unsigned();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
        });
        
        Schema::create('product_amount', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->integer('product_color_id')->unsigned();
            $table->integer('category_size_id')->unsigned();
            $table->timestamps();
            $table->foreign('product_color_id')->references('id')->on('product_colors');
            $table->foreign('category_size_id')->references('id')->on('category_sizes');
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('spanish');
            $table->text('english');
            $table->timestamps();
        });

        Schema::create('social_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            $table->text('youtube')->nullable();
            $table->text('horario_atc')->nullable();
            $table->text('horario_entrega')->nullable();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0: Inactivo; 1: Activo; 2: Eliminado;');
            $table->timestamps();
        });

        Schema::create('blog_images', function (Blueprint $table) {
            $table->increments('id');
            $table->text('file');
            $table->integer('blog_id')->unsigned();
            $table->foreign('blog_id')->references('id')->on('blogs');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('category_sizes');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('designs');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_colors');
        Schema::dropIfExists('product_amount');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('social_networks');
        Schema::dropIfExists('blogs');        
        Schema::dropIfExists('blog_images');        
    }
}
