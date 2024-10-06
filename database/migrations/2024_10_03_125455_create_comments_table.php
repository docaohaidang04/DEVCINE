<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment');
            $table->unsignedBigInteger('id_movie');
            $table->unsignedBigInteger('id_account');
            $table->text('content');
            $table->integer('rating');
            $table->timestamps();

            $table->foreign('id_movie')->references('id_movie')->on('movies');
            $table->foreign('id_account')->references('id_account')->on('accounts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}