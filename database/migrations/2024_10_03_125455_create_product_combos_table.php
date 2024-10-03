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
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_account');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->foreign('id_product')->references('id_product')->on('products');
            $table->foreign('id_account')->references('id_account')->on('accounts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_combos');
    }
}
