<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopConfigs extends Migration
{
    /**
     * 商户配置表-shop_configs
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('shop_id')->default(0)->comment('店铺ID');
            $table->string('shop_title',255)->default('')->comment('标题');
            $table->string('shop_keyword',255)->default('')->comment('关键字');
            $table->string('shop_describe',255)->default('')->comment('描述');
            $table->string('shop_banner',255)->default('')->comment('头');
            $table->text('shop_ads')->default('')->comment('广告');
            $table->text('shop_urls')->default('')->comment('广告链接');
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
        Schema::dropIfExists('shop_configs');
    }
}
