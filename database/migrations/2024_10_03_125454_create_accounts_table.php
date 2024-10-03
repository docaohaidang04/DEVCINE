<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id('id_account');
            $table->string('user_name')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('full_name');
            $table->string('phone');
            $table->string('role');
            $table->integer('loyalty_points')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
