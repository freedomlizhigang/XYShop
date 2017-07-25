<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetobuy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetobuy', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('good_id')->default(0)->comment('商品ID');
            $table->string('good_title')->default('')->comment('商品标题');
            $table->string('title')->default('')->comment('标题');
            $table->decimal('price',10,2)->default(0)->comment('抢购价');
            $table->integer('good_num')->default(0)->comment('参加数量');
            $table->integer('buy_max')->default(0)->comment('每人限购数量');
            $table->integer('buy_num')->default(0)->comment('已买人数');
            $table->integer('order_num')->default(0)->comment('已下单数量');
            $table->string('describe',255)->default('')->comment('描述');
            $table->timestamp('starttime')->comment('开始时间');
            $table->timestamp('endtime')->comment('结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态');
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
        Schema::dropIfExists('timetobuy');
    }
}
