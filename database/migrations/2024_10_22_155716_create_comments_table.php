<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('ID_COMMENT'); // Khóa chính
            $table->foreignId('ID_MOVIES')->constrained('movies', 'ID_MOVIE')->onDelete('cascade'); // FK: ID_MOVIES
            $table->foreignId('ID_ACCOUNT')->constrained('accounts', 'ID_ACCOUNT')->onDelete('cascade'); // FK: ID_ACCOUNT
            $table->text('Content'); // Nội dung bình luận
            $table->unsignedTinyInteger('Rating'); // Đánh giá (1-5)
            $table->timestamps(); // Created_at, Updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
