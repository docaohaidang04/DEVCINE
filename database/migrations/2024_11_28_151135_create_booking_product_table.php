<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingProductTable extends Migration
{
    public function up()
    {
        Schema::create('booking_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_booking')->constrained('bookings', 'id_booking')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('products', 'id_product')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_product');
    }
}
