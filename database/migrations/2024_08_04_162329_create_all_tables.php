<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tandatangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('jabatan', 200);
            $table->string('nip', 25);
            $table->timestamps();
        });

        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kop');
            $table->date('tanggal');
            $table->string('no_surat');
            $table->string('asal_surat');
            $table->string('perihal');
            $table->string('disp1');
            $table->string('disp2');
            $table->unsignedBigInteger('id_tandatangan');
            $table->string('image');
            $table->timestamps();

            $table->foreign('id_kop')->references('id')->on('kepala_surat');
            $table->foreign('id_tandatangan')->references('id')->on('tandatangan');
        });

        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kop');
            $table->date('tanggal');
            $table->string('no_surat');
            $table->string('perihal');
            $table->string('tujuan');
            $table->text('isi_surat');
            $table->unsignedBigInteger('id_tandatangan');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('id_kop')->references('id')->on('kepala_surat');
            $table->foreign('id_tandatangan')->references('id')->on('tandatangan');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nama_tandatangan');
        Schema::dropIfExists('surat_masuk');
        Schema::dropIfExists('surat_keluar');
    }
};
