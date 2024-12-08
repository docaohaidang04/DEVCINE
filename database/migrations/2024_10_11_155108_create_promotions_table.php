<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('id_promotion');
            $table->string('promotion_name');
            $table->text('description')->nullable();
            $table->integer('min_purchase_amount')->nullable()->default(0);
            $table->integer('max_discount_amount')->nullable()->default(0);
            $table->integer('promotion_point')->default(0);
            $table->string('discount_type');
            $table->integer('discount_value')->nullable()->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
