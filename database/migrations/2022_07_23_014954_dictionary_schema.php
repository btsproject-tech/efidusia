<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DictionarySchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('dictionary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('term_id', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->string('context', 255)->nullable();
            $table->text('file')->nullable();
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
        Schema::dropIfExists('dictionary');
    }
}
