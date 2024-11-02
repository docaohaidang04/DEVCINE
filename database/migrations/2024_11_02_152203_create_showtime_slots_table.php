<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowtimeSlotsTable extends Migration
{
    public function up()
    {
        Schema::create('showtime_slots', function (Blueprint $table) {
            $table->id('id_slot');
            $table->time('slot_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('showtime_slots');
    }
}
