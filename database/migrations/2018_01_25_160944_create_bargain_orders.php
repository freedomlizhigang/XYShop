<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBargainOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bargain_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('bargin_id')->default(0)->comment('活动ID');
            $table->string('bargin_title',255)->default('')->comment('活动标题');
            $table->integer('good_id')->default(0)->comment('商品ID');
            $table->decimal('old_price',10,2)->default(0)->comment('价格');
            $table->decimal('price',10,2)->default(0)->comment('价格');
            $table->smallInteger('nums')->default(0)->comment('已砍多少次');
            $table->tinyInteger('status')->default(1)->comment('状态：1进行中，0关闭，2完成');
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
        Schema::dropIfExists('bargain_orders');
    }
}
