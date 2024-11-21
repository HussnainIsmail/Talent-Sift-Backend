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
            $table->json('jobType')->nullable();  // JSON field for multiple job types
            $table->json('workLocation')->nullable();  // JSON field for multiple locations
            $table->boolean('subscribe')->default(false);  // Subscription status
            $table->string('image')->nullable();  // Optional image field
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
