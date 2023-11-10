<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksSettingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks_setting_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('indicator_id')->nullable();
            $table->string('marks')->nullable();

            $table->timestamps();
            $table->foreign('main_id')->references('id')->on('marks_settings');
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks_setting_details');
    }
}
