<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopUsers extends Migration
{
    /**
     * 职员shop_users
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('shop_id')->default(0)->comment('店铺ID');
            $table->integer('role_id')->default(0)->comment('职员角色ID');
            $table->string('username',50)->default('')->comment('职员用户名');
            $table->string('pwd',255)->default('')->comment('职员密码');
            $table->char('crypt',10)->default('')->comment('安全码');
            $table->string('realname',50)->default('')->comment('真实姓名');
            $table->string('email',100)->default('')->comment('邮箱');
            $table->string('phone',20)->default('')->comment('电话');
            $table->timestamp('lasttime')->nullable()->comment('最后登陆时间');
            $table->string('lastip',20)->default('')->comment('最后登陆IP');
            $table->tinyInteger('status')->default(1)->comment('状态，1正常0禁用');
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
        Schema::dropIfExists('shop_users');
    }
}
