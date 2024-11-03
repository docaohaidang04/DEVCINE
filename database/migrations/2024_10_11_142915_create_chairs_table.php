<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChairsTable extends Migration
{
    public function up()
    {
        Schema::create('chairs', function (Blueprint $table) {
            $table->id('id_chair');
            $table->foreignId('id_room')->constrained('rooms', 'id_room');
            $table->string('chair_name')->nullable();
            $table->string('chair_status')->nullable();
            $table->integer('column')->nullable();
            $table->integer('row')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chairs');
    }
}