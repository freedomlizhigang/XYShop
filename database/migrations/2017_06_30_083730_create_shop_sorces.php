<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopSorces extends Migration
{
    /**
     * 商户评分表-shop_sorces
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_sorces', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('shop_id')->default(0)->comment('店铺ID');
            $table->decimal('total_sorce',10,2)->default(0)->comment('总分');
            $table->integer('total_users')->default(0)->comment('总评分用户数');
            $table->decimal('good_sorce',10,2)->default(0)->comment('商品评分');
            $table->integer('good_users')->default(0)->comment('商品评分用户数');
            $table->decimal('service_sorce',10,2)->default(0)->comment('服务评分');
            $table->integer('service_users')->default(0)->comment('服务评分用户数');
            $table->decimal('time_sorce',10,2)->default(0)->comment('时效评分');
            $table->integer('time_users')->default(0)->comment('时效评分用户数');
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
        Schema::dropIfExists('shop_sorces');
    }
}
