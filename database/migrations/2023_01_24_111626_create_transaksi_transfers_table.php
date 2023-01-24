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
        Schema::create('transaksi_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_request_id');
            $table->foreign('transaksi_request_id')->references('id')->on('transaksi_requests')->onDelete('cascade');
            $table->string('id_transaksi',20);
            $table->float('nilai_transfer',15);
            $table->integer('kode_unik');
            $table->float('biaya_admin',15)->default(0);
            $table->float('total_transfer',15);
            $table->string('bank_perantara',100);
            $table->string('rekening_perantara',100);
            $table->dateTime('berlaku_hingga');
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
        Schema::dropIfExists('transaksi_transfers');
    }
};
