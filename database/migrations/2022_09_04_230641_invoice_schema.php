<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_invoice', 255)->nullable();
            $table->integer('shipping_excecution')->nullable();
            $table->integer('document')->nullable();
            $table->date('invoice_date')->nullable();
            $table->integer('customer_name')->nullable(); //master customer
            $table->string('roe', 255)->nullable();
            $table->string('currency', 255)->nullable(); //data currency
            $table->timestamp('deleted')->nullable(); 
            $table->double('amount')->nullable(); //ada formulanya
            $table->double('total')->nullable(); //ada formulanya
            $table->double('tax')->nullable(); //ada formulanya
            $table->double('grand_total')->nullable(); //ada formulanya
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
        Schema::dropIfExists('invoice');
    }
}
