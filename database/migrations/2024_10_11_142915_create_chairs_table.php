<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChairsTable extends Migration
{
    public function up()
    {
        Schema::create('chairs', function (Blueprint $table) {
            $table->id('ID_CHAIR');
            $table->foreignId('ID_ROOM')->constrained('rooms', 'ID_ROOM');
            $table->string('Chair_name')->nullable();
            $table->string('Chair_status')->nullable();
            $table->integer('Column')->nullable();
            $table->integer('Row')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chairs');
    }
}
