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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 30);
            $table->date('date');
            $table->time('time_in', 0)->nullable();
            $table->string('longitude_in')->nullable();
            $table->string('latitude_in')->nullable();
            $table->string('images_in')->nullable();
            $table->time('time_out', 0)->nullable();
            $table->string('longitude_out')->nullable();
            $table->string('latitude_out')->nullable();
            $table->string('images_out')->nullable();
            $table->string('schedule_code')->nullable();
            $table->string('absensi_ref')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('user_id')->references('id')->on('master_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi');
    }
};
