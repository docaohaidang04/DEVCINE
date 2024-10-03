<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('id_ticket');
            $table->unsignedBigInteger('id_booking');
            $table->string('seat_number');
            $table->decimal('price', 8, 2);
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_booking')->references('id_booking')->on('bookings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
