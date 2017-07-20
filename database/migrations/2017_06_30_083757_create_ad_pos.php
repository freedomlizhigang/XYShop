<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdPos extends Migration
{
    /**
     * 广告位表-ad_pos
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_pos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->tinyInteger('is_mobile')->default(0)->comment('0:PC/1:MOB');
            $table->string('name',100)->default('')->comment('名称');
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
        Schema::dropIfExists('ad_pos');
    }
}
