<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAds extends Migration
{
    /**
     * 广告表-ads
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('pos_id')->default(0)->comment('位置ID');
            $table->string('title',255)->default('')->comment('标题');
            $table->string('thumb',255)->default('')->comment('图片');
            $table->string('url',255)->default('')->comment('链接');
            $table->timestamp('starttime',255)->nullable()->comment('开始时间');
            $table->timestamp('endtime',255)->nullable()->comment('结束时间');
            $table->mediumInteger('sort')->default(0)->unsigned()->comment('排序');
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
        Schema::dropIfExists('ads');
    }
}
