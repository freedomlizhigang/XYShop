<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopLog extends Migration
{
    /**
     * 职员记录shop_log
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('职员ID');
            $table->string('username',50)->default('')->comment('职员用户名');
            $table->string('url',255)->default('')->comment('操作URL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_log');
    }
}
