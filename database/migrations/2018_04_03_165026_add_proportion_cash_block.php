<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProportionCashBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sign_config', function (Blueprint $table) {
            $table->integer('proportion')->default(0)->comment('可折现比例')->after('id');
            $table->integer('cash')->default(0)->comment('抵扣比例')->after('proportion');
            $table->integer('block')->default(0)->comment('分段')->after('cash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sign_config', function (Blueprint $table) {
            //
        });
    }
}
