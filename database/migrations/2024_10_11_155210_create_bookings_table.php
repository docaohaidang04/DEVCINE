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
            $table->foreignId('account_promotion_id')->nullable()->constrained('account_promotion', 'account_promotion_id');
            $table->foreignId('id_payment')->nullable()->constrained('payment', 'id_payment');
            $table->foreignId('id_ticket')->nullable()->constrained('tickets', 'id_ticket');
            $table->string('booking_code')->nullable();
            $table->dateTime('booking_date')->default(now());
            $table->integer('total_amount')->nullable()->default(0);
            $table->string('payment_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
