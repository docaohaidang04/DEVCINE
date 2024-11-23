<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombosTable extends Migration
{
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id('id_combo');
            $table->string('name');
            $table->integer('price')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->string('image_combos')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('combos');
    }
}
