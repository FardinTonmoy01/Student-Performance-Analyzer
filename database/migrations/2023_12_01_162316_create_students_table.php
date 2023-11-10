<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('auto_id', 50);
            $table->string('roll_no', 50);
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('shift_id');

            $table->date('dob');
            $table->tinyInteger('gender')->default(0)->commnet('1=Male', '2=Female');
            $table->string('birth_certificate', 100)->nullable();
            $table->string('address');
            $table->string('per_address');

            $table->string('father_name', 100)->nullable();
            $table->string('f_mobile_no', 15)->nullable();
            $table->string('f_occupation')->nullable();

            $table->string('mother_name', 100)->nullable();
            $table->string('m_mobile_no', 15)->nullable();
            $table->string('m_occupation')->nullable();

            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('institute_id')->references('id')->on('institute_informations');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('class_id')->references('id')->on('classes');
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
        Schema::dropIfExists('students');
    }
}
