<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->unsignedBigInteger('id_account');
            $table->unsignedBigInteger('id_showtime');
            $table->unsignedBigInteger('id_product_combo')->nullable();
            $table->unsignedBigInteger('id_payment');
            $table->date('booking_date');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status');
            $table->string('transaction_id')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_account')->references('id_account')->on('accounts');
            $table->foreign('id_showtime')->references('id_showtime')->on('showtimes');
            $table->foreign('id_product_combo')->references('id_product_combo')->on('product_combos');
            $table->foreign('id_payment')->references('id_payment')->on('payments');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
