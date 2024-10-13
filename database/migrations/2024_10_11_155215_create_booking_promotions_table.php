<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('booking_promotions', function (Blueprint $table) {
            $table->id('ID_PROMOTION_AP');
            $table->foreignId('ID_PROMOTION')->constrained('promotions', 'ID_PROMOTION');
            $table->foreignId('ID_BOOKING')->constrained('bookings', 'ID_BOOKING');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_promotions');
    }
}
