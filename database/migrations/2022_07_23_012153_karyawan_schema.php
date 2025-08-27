<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KaryawanSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('karyawan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company')->nullable();
            $table->string('nik', 255)->nullable();
            $table->string('nama_lengkap', 255)->nullable();
            $table->string('jabatan', 255)->nullable();
            $table->string('contact', 255)->nullable();
            $table->string('email', 255)->nullable();
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
        Schema::dropIfExists('karyawan');
    }
}
