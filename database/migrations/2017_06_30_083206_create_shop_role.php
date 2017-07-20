<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopRole extends Migration
{
    /**
     * 职员角色-shop_role
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name',50)->default('')->comment('角色名');
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
        Schema::dropIfExists('shop_role');
    }
}
