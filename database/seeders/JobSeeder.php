<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert a single hardcoded job record
        Job::create([
            'jobtitle' => 'Software Engineer',
            'email' => 'softwareengineer@example.com',
            'description' => 'A software engineer responsible for building and maintaining applications.',
            'jobType' => json_encode(['full-time', 'part-time']),// Example of job types as an array
            'workLocation' => json_encode(['remote']),  // Example of work location as an array
            'subscribe' => true, // Subscribe status as true
            'image' => null, // No image for this example
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
