<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserPermissionSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('users_group')->nullable();
            $table->integer('menu')->nullable();
            $table->integer('insert')->nullable();
            $table->integer('update')->nullable();
            $table->integer('delete')->nullable();
            $table->integer('view')->nullable();
            $table->integer('print')->nullable();
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
        Schema::dropIfExists('users_permissions');
    }
}
