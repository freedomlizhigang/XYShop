<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_config', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('son_proportion')->default(0)->comment('一级返现比例（子）');
            $table->decimal('sun_proportion')->default(0)->comment('二级返现比例（孙）');
            $table->tinyInteger('unlock')->default(1)->comment('是否开启，1是，0否');
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
        Schema::dropIfExists('distribution_config');
    }
}
