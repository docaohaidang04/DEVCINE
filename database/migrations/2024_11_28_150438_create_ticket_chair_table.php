<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketChairTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ticket_chair')) {
            Schema::create('ticket_chair', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
                $table->foreignId('chair_id')->constrained('chairs')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('ticket_chair');
    }
}
