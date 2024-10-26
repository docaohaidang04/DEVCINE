<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombosTable extends Migration
{
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id('ID_COMBO');
            $table->string('Name');
            $table->integer('Price')->default(0);
            $table->text('Description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('combos');
    }
}
