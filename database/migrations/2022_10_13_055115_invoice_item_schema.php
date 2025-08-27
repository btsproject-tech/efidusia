<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceItemSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('invoice_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice')->nullable();
            $table->string('subject', 255)->nullable();
            $table->string('currency', 255)->nullable();
            $table->double('rate')->nullable();
            $table->string('unit', 255)->nullable();
            $table->string('remarks', 110)->nullable();
            $table->double('qty')->nullable();
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
        Schema::dropIfExists('invoice_item');
    }
}
