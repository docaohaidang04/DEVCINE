<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id('ID_ACCOUNT');
            $table->string('User_name');
            $table->string('Password');
            $table->string('Email');
            $table->string('Full_name')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Role')->nullable();
            $table->integer('Loyalty_points')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
