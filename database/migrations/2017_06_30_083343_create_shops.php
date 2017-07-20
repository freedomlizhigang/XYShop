<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShops extends Migration
{
    /**
     * 商户表-shops
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('shop_catid')->default(0)->comment('门店分类ID');
            $table->integer('areaid1')->default(0)->comment('省');
            $table->integer('areaid2')->default(0)->comment('市');
            $table->integer('areaid3')->default(0)->comment('县');
            $table->string('shopsn',20)->default('')->comment('门店编号');
            $table->string('username',50)->default('')->comment('用户名');
            $table->string('passwd',255)->default('')->comment('密码');
            $table->char('crypt',10)->default('')->comment('安全码');
            $table->string('email',100)->default('')->comment('邮箱');
            $table->string('shop_name',255)->default('')->comment('店名');
            $table->string('shop_company',255)->default('')->comment('公司名');
            $table->string('shop_img',255)->default('')->comment('门店标识');
            $table->string('shop_tel',50)->default('')->comment('电话');
            $table->string('shop_address',255)->default('')->comment('地址');
            $table->string('shop_qq',50)->default('')->comment('QQ');
            $table->string('scale',255)->default('')->comment('规模');
            $table->tinyInteger('isself')->default(0)->comment('是否自营：0否，1是');
            $table->tinyInteger('is_distribut_all')->default(1)->comment('是否全国配送：0否，1是');
            $table->decimal('avg_cost_money',10,2)->default(0)->comment('平均消费金额');
            $table->tinyInteger('isinvoice')->default(0)->comment('是否开发票：0否，1是');
            $table->string('invoicemark',255)->default('')->comment('发票说明');
            $table->timestamp('service_starttime')->nullable()->comment('开始营业时间');
            $table->timestamp('service_endtime')->nullable()->comment('结束营业时间');
            $table->tinyInteger('shop_active')->default(1)->comment('店铺营业状态：0关闭，1正常');
            $table->tinyInteger('shop_status')->default(0)->comment('店铺状态：-2:已停止 -1:拒绝 0：未审核 1:已审核');
            $table->string('status_mark',255)->default('')->comment('一般用于停止和拒绝说明');
            $table->tinyInteger('ispos')->default(0)->comment('是否推荐：0否，1是');
            $table->mediumInteger('sort')->default(0)->unsigned()->comment('排序');
            $table->tinyInteger('delflag')->default(1)->comment('删除状态，1正常-1删除');
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
        Schema::dropIfExists('shops');
    }
}
