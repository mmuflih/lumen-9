<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_passwords', function (Blueprint $table) {
            $table->string('reset_token')->nullable()->after('active');
            $table->bigInteger('token_expired_at')->after('reset_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_passwords', function (Blueprint $table) {
            $table->dropColumn('reset_token');
            $table->dropColumn('token_expired_at');
        });
    }
};
