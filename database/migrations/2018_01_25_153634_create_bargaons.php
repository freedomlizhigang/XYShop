<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBargaons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bargains', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('good_id')->default(0)->comment('商品ID');
            $table->string('good_title',255)->default('')->comment('商品标题');
            $table->string('title',255)->default('')->comment('标题');
            $table->string('describe',500)->default('')->comment('描述');
            $table->decimal('price',10,2)->default(0)->comment('价格');
            $table->integer('store')->default(0)->comment('库存');
            $table->decimal('bargain_price',10,2)->default(0)->comment('每次砍价格');
            $table->smallInteger('maxnum')->default(0)->comment('最多砍几次');
            $table->integer('numpeople')->default(0)->comment('参与人数');
            $table->timestamp('starttime')->nullable()->comment('开始时间');
            $table->timestamp('endtime')->nullable()->comment('结束时间');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态：1正常，0关闭');
            $table->tinyInteger('delflag')->default(0)->comment('删除状态：0正常，1关闭');
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
        Schema::dropIfExists('bargains');
    }
}
