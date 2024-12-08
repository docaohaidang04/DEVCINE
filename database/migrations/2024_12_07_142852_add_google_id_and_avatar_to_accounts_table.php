<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleIdAndAvatarToAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('avatar')->nullable()->after('google_id');
        });
    }

    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar']);
        });
    }
}
