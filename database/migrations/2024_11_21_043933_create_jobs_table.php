<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('jobtitle');
            $table->string('email');
            $table->text('description');
            $table->boolean('subscribe')->default(false);
            $table->string('image')->nullable();
            $table->unsignedBigInteger('minSalary')->nullable(); // Regular unsigned integer
            $table->unsignedBigInteger('maxSalary')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
