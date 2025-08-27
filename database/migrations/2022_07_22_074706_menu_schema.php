<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MenuSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('menu_alias', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('parent', 255)->nullable();
            $table->integer('sort')->nullable();
            $table->string('routing_mobile', 255)->nullable();
            $table->string('icon_mobile', 255)->nullable();
            $table->timestamp('deleted')->nullable();
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
        //
        Schema::dropIfExists('menu');
    }
}
