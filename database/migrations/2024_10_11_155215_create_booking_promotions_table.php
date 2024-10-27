<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('booking_promotions', function (Blueprint $table) {
            $table->id('id_promotion_ap');
            $table->foreignId('id_promotion')->constrained('promotions', 'id_promotion');
            $table->foreignId('id_booking')->constrained('bookings', 'id_booking');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_promotions');
    }
}
