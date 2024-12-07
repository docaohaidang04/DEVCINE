<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToBookingProductTable extends Migration
{
    public function up()
    {
        Schema::table('booking_product', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('id_product');
        });
    }

    public function down()
    {
        Schema::table('booking_product', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
}
