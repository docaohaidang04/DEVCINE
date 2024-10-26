<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('ID_BOOKING');
            $table->foreignId('ID_ACCOUNT')->constrained('accounts', 'ID_ACCOUNT');
            $table->foreignId('ID_COMBO')->nullable()->constrained('combos', 'ID_COMBO');
            $table->foreignId('ID_PAYMENT')->nullable()->constrained('payment', 'ID_PAYMENT');
            $table->dateTime('Booking_date')->default(now());
            $table->integer('Quantity')->nullable();
            $table->integer('Total_amount')->nullable()->default(0);
            $table->string('Payment_status')->nullable();
            $table->string('Transaction_id')->nullable();
            $table->dateTime('Payment_date')->nullable();
            $table->string('Status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
