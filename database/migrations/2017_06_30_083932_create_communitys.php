<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunitys extends Migration
{
    /**
     * 社区表-communitys
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communitys', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('areaid1')->default(0)->comment('省');
            $table->integer('areaid2')->default(0)->comment('市');
            $table->integer('areaid3')->default(0)->comment('县');
            $table->string('name',255)->default('')->comment('名称');
            $table->tinyInteger('is_show')->default(1)->comment('是否显示：0否，1是');
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
        Schema::dropIfExists('communitys');
    }
}
