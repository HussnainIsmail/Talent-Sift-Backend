<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            // Company 1 (id: 1)
            [
                'user_id' => 19,
                'company_id' => 7,
                'jobtitle' => 'Frontend Developer',
                'email' => 'jobs@techvision.com',
                'description' => 'Develop responsive frontends using React or Vue.js.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 50000,
                'maxSalary' => 90000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 19,
                'company_id' => 7,
                'jobtitle' => 'Laravel Backend Engineer',
                'email' => 'backend@techvision.com',
                'description' => 'Build scalable APIs using Laravel and MySQL.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 60000,
                'maxSalary' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Company 2 (id: 2)
            [
                'user_id' => 20,
                'company_id' => 8,
                'jobtitle' => 'Graphic Designer',
                'email' => 'design@creativeminds.com',
                'description' => 'Design engaging marketing visuals and brand kits.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 40000,
                'maxSalary' => 70000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 20,
                'company_id' => 8,
                'jobtitle' => 'UI/UX Designer',
                'email' => 'ux@creativeminds.com',
                'description' => 'Design wireframes and interactive mockups using Figma.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 50000,
                'maxSalary' => 85000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Company 3 (id: 3)
            [
                'user_id' => 21,
                'company_id' => 9,
                'jobtitle' => 'AI Engineer',
                'email' => 'ai@nextgen.com',
                'description' => 'Work on ML models and AI-based systems.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 80000,
                'maxSalary' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 21,
                'company_id' => 9,
                'jobtitle' => 'DevOps Engineer',
                'email' => 'devops@nextgen.com',
                'description' => 'Setup CI/CD pipelines and manage cloud infrastructure.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 70000,
                'maxSalary' => 130000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Company 4 (id: 4)
            [
                'user_id' => 22,
                'company_id' => 10,
                'jobtitle' => 'Business Analyst',
                'email' => 'analyst@bizware.com',
                'description' => 'Analyze business requirements and document workflows.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 55000,
                'maxSalary' => 90000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 22,
                'company_id' => 10,
                'jobtitle' => 'ERP Consultant',
                'email' => 'erp@bizware.com',
                'description' => 'Consult clients on ERP modules and manage implementations.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 60000,
                'maxSalary' => 110000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Company 5 (id: 5)
            [
                'user_id' => 23,
                'company_id' => 11,
                'jobtitle' => 'Mobile App Developer',
                'email' => 'apps@elitesoft.com',
                'description' => 'Develop native and cross-platform apps for Android and iOS.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 65000,
                'maxSalary' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 23,
                'company_id' => 11,
                'jobtitle' => 'API Developer',
                'email' => 'api@elitesoft.com',
                'description' => 'Build secure APIs for mobile and web applications.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 60000,
                'maxSalary' => 110000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('jobs')->insert($jobs);
    }
}
