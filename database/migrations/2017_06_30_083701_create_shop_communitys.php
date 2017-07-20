<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCommunitys extends Migration
{
    /**
     * 商户配送区域表-shop_communitys
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_communitys', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('shop_id')->default(0)->comment('店铺ID');
            $table->integer('areaid1')->default(0)->comment('省');
            $table->integer('areaid2')->default(0)->comment('市');
            $table->integer('areaid3')->default(0)->comment('县');
            $table->integer('communityid')->default(0)->comment('社区');
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
        Schema::dropIfExists('shop_communitys');
    }
}
