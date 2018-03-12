<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('son_id')->default(0)->comment('子级ID');
            $table->integer('sun_id')->default(0)->comment('孙级ID');
            $table->integer('order_id')->default(0)->comment('订单ID');
            $table->decimal('money')->default(0)->comment('金额');
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
        Schema::dropIfExists('distribution_logs');
    }
}
