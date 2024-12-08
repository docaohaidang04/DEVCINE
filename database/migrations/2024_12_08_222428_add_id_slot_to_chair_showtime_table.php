<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdSlotToChairShowtimeTable extends Migration
{
    public function up()
    {
        Schema::table('chair_showtime', function (Blueprint $table) {
            $table->unsignedBigInteger('id_slot')->after('id_showtime'); // Thêm cột id_slot sau id_showtime
            $table->foreign('id_slot')->references('id_slot')->on('showtime_slots')->onDelete('cascade'); // Thêm khóa ngoại
        });
    }

    public function down()
    {
        Schema::table('chair_showtime', function (Blueprint $table) {
            $table->dropForeign(['id_slot']); // Xóa khóa ngoại
            $table->dropColumn('id_slot'); // Xóa cột id_slot
        });
    }
}
