<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCombosTable extends Migration
{
    public function up()
    {
        Schema::create('product_combos', function (Blueprint $table) {
            $table->id('ID_PRODUCT_COMBO');
            $table->foreignId('ID_COMBO')->constrained('combos', 'ID_COMBO');
            $table->foreignId('ID_PRODUCT')->constrained('products', 'ID_PRODUCT');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_combos');
    }
}
