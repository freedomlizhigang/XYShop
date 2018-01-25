<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSignLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sign_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('point')->default(0)->comment('积分数变化');
            $table->tinyInteger('type')->default(1)->comment('1增加，0消费');
            $table->smallInteger('days')->default(0)->comment('累计天数');
            $table->timestamp('signtime')->nullable()->comment('签到日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sign_logs');
    }
}
