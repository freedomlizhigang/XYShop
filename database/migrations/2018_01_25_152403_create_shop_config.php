<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_config', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('onepoint')->default(0)->comment('每次几分');
            $table->smallInteger('days')->default(0)->comment('连续几天有奖励');
            $table->smallInteger('reward')->default(0)->comment('奖励几分');
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
        Schema::dropIfExists('sign_config');
    }
}
