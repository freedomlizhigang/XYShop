<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistribution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->integer('father_id')->default(0)->comment('祖级ID');
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
        Schema::dropIfExists('distribution');
    }
}
