<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsApTable extends Migration
{
    public function up()
    {
        Schema::create('promotions_ap', function (Blueprint $table) {
            $table->id('id_promotion_ap');
            $table->unsignedBigInteger('id_promotion');
            $table->unsignedBigInteger('id_booking');
            $table->decimal('discount_amount', 8, 2);
            $table->timestamps();

            $table->foreign('id_promotion')->references('id_promotion')->on('promotions');
            $table->foreign('id_booking')->references('id_booking')->on('bookings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions_ap');
    }
}
