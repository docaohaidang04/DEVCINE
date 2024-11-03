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
            $table->foreignId('id_booking')->constrained('bookings', 'id_booking')->nullable();
            $table->foreignId('id_showtime')->constrained('showtimes', 'id_showtime');
            $table->foreignId('id_chair')->constrained('chairs', 'id_chair');
            $table->integer('price')->nullable()->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
