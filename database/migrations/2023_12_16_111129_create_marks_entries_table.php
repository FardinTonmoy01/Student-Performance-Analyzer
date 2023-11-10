<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('indicator_id');

            $table->tinyInteger('status')->default(0)->comment('0=Draft, 1=Final');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('institute_id')->references('id')->on('institute_informations');
            $table->foreign('class_id')->references('id')->on('classes');

            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('indicator_id')->references('id')->on('evaluating_indicators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks_entries');
    }
}
