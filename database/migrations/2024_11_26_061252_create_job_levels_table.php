<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('job_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level');  // Entry, Middle, Expert
            $table->unsignedBigInteger('job_id');
            $table->timestamps();

            // Foreign key relationship to jobs table
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_levels');
    }
}
