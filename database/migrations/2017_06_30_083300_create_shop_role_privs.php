<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopRolePrivs extends Migration
{
    /**
     * 职员权限表shop_role_privs
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_role_privs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('role_id')->default(0)->comment('职员角色ID');
            $table->integer('menu_id')->default(0)->comment('菜单ID');
            $table->string('url',200)->comment('菜单URL');
            $table->string('label',200)->comment('菜单LABEL');
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
        Schema::dropIfExists('shop_role_privs');
    }
}
