<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up()
    {
        Schema::create('master_user', function (Blueprint $table) {
            $table->id();
            $table->string('user', 30)->unique();
            $table->string('description', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('nik', 50)->nullable();
            $table->integer('departmentid')->nullable();
            $table->integer('unitid')->nullable();
            $table->timestamp('entrytime')->useCurrent()->useCurrentOnUpdate();
            $table->string('entryuser', 255)->nullable();
            $table->string('entryip', 255)->nullable();
            $table->timestamp('updatetime')->nullable();
            $table->string('updateuser', 255)->nullable();
            $table->string('updateip', 255)->nullable();
            $table->text('avatar')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_user');
    }
};
