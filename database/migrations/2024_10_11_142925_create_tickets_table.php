<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ID_TICKET');
            $table->foreignId('ID_BOOKING')->constrained('bookings', 'ID_BOOKING')->nullable();
            $table->foreignId('ID_SHOWTIME')->constrained('showtimes', 'ID_SHOWTIME');
            $table->foreignId('ID_CHAIR')->constrained('chairs', 'ID_CHAIR');
            $table->integer('Price')->nullable()->default(0);
            $table->string('Status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
