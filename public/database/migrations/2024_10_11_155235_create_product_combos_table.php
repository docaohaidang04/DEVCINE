<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCombosTable extends Migration
{
    public function up()
    {
        Schema::create('product_combos', function (Blueprint $table) {
            $table->id('id_product_combo');
            $table->foreignId('id_product')->constrained('products', 'id_product');
            $table->foreignId('id_combo')->constrained('combos', 'id_combo');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_combos');
    }
}
