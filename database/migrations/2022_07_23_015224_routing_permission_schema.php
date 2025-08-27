<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoutingPermissionSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('routing_permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('routing_header')->nullable();
            $table->integer('menu')->nullable();
            $table->string('prev_state', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('nik', 255)->nullable();
            $table->integer('is_active')->nullable();
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
        Schema::dropIfExists('routing_permission');
    }
}
