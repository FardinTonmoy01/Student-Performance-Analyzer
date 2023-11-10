<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituteInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('inst_name', 191);
            $table->string('logo', 100);
            $table->string('email', 150);
            $table->string('phone', 15);
            $table->string('address');
            $table->string('establishment_year', 4);
            $table->text('description')->nullable();
            $table->tinyInteger('sorting_order')->default(1);
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institute_informations');
    }
}
