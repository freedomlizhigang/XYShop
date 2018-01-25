<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBargaonLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bargain_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bargain_id')->default(0)->comment('砍价活动id');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->decimal('price',10,2)->default(0)->comment('砍掉金额');
            $table->timestamp('bargaintime')->nullable()->comment('砍价时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bargain_logs');
    }
}
