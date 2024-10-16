<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('ID_PROMOTION');
            $table->string('Promotion_name');
            $table->text('Description')->nullable();
            $table->decimal('Discount_value', 10, 2)->nullable();
            $table->dateTime('Start_date')->nullable();
            $table->dateTime('End_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}