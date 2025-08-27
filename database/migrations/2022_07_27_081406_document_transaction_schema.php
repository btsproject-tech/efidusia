<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DocumentTransactionSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_document', 255)->nullable();
            $table->integer('actors')->nullable();
            $table->string('state', 255)->nullable();
            $table->string('remarks', 255)->nullable();
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
        Schema::dropIfExists('document_transaction');
    }
}
