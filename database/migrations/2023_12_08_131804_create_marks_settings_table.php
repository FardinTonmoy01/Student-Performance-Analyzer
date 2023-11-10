<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('group_id')->nullable();

            $table->tinyInteger('status')->default(0)->comment('0=Draft, 1=Final');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('institute_id')->references('id')->on('institute_informations');
            $table->foreign('class_id')->references('id')->on('classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks_settings');
    }
}
