<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoutingHeaderSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('routing_header', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_group')->nullable();
            $table->integer('menu')->nullable();
            $table->integer('company')->nullable();
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
        Schema::dropIfExists('routing_header');
    }
}
