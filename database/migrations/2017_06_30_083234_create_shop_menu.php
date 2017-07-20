<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopMenu extends Migration
{
    /**
     * 职员菜单-shop_menu
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parentid')->index()->comment('父ID，索引');
            $table->string('arrparentid')->default(0)->comment('所有父ID');
            $table->tinyInteger('child')->default(0)->comment('是否有子栏目');
            $table->mediumText('arrchildid')->comment('所有子栏目ID');
            $table->string('name',100)->comment('菜单名称');
            $table->string('url',200)->comment('菜单URL');
            $table->string('label',200)->comment('菜单LABEL');
            $table->tinyInteger('is_show')->default(1)->comment('显示，1是0否');
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
        Schema::dropIfExists('shop_menu');
    }
}
