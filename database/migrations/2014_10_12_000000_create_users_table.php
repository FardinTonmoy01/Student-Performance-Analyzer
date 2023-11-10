<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 50);
            $table->string('email', 100)->unique();
            $table->string('photo', 100)->nullable();
            $table->string('mobile_no', 15)->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('type')->default(0)->comment('1=Super Admin, 2=Admin, 3=Teacher, 4=Parent, 5=Student');
            $table->tinyInteger('status')->default(0)->comment('1=Active, 0=Inactive');
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
