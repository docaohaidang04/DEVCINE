<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChairShowtimeTable extends Migration
{
    public function up()
    {
        Schema::create('chair_showtime', function (Blueprint $table) {
            $table->id(); // ID chính cho bảng
            $table->unsignedBigInteger('id_chair'); // Khóa ngoại tới bảng chairs
            $table->unsignedBigInteger('id_showtime'); // Khóa ngoại tới bảng showtimes
            $table->unsignedBigInteger('id_slot'); // Khóa ngoại tới bảng slots (nếu cần)
            $table->string('chair_status')->default('available'); // Trạng thái ghế
            $table->timestamps(); // Thời gian tạo và cập nhật

            $table->foreign('id_chair')->references('id_chair')->on('chairs')->onDelete('cascade');
            $table->foreign('id_showtime')->references('id_showtime')->on('showtimes')->onDelete('cascade');
            $table->foreign('id_slot')->references('id_slot')->on('showtime_slots')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chair_showtime');
    }
}
