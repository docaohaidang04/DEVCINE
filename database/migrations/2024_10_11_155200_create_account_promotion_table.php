<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPromotionTable extends Migration
{
    public function up()
    {
        Schema::create('account_promotion', function (Blueprint $table) {
            $table->id('account_promotion_id');
            $table->foreignId('account_id')->constrained('accounts', 'id_account');
            $table->foreignId('promotion_id')->constrained('promotions', 'id_promotion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_promotion');
    }
}
