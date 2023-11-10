<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('auto_id', 50);
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('user_id');
            $table->date('dob');
            $table->tinyInteger('gender')->default(0)->commnet('1=Male', '2=Female');
            $table->string('nid');
            $table->string('address');
            $table->string('per_address');
            $table->text('edu_qualification');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('institute_id')->references('id')->on('institute_informations');
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
        Schema::dropIfExists('teachers');
    }
}
