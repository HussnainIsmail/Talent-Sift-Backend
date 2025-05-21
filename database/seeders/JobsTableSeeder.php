<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\JobType;
use App\Models\WorkLocation;
use App\Models\JobLevel;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            // Company 1 (id: 1)
            [
                'user_id' => 6,
                'company_id' => 1,
                'jobtitle' => 'Frontend Developer',
                'email' => 'jobs@techvision.com',
                'description' => 'Develop responsive frontends using React or Vue.js.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 50000,
                'maxSalary' => 90000,
                'experience' => '1-2 years',
                'skills' => ['JavaScript', 'React', 'CSS'],
                'jobTypes' => ['Full-Time', 'Remote'],
                'workLocations' => ['Lahore'],
                'jobLevels' => ['Junior'],
            ],
            [
                'user_id' => 6,
                'company_id' => 1,
                'jobtitle' => 'Laravel Backend Engineer',
                'email' => 'backend@techvision.com',
                'description' => 'Build scalable APIs using Laravel and MySQL.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 60000,
                'maxSalary' => 100000,
                'experience' => '3+ years',
                'skills' => ['Laravel', 'PHP', 'MySQL'],
                'jobTypes' => ['Full-Time'],
                'workLocations' => ['Remote'],
                'jobLevels' => ['Mid'],
            ],

            // Company 2 (id: 2)
            [
                'user_id' => 7,
                'company_id' => 2,
                'jobtitle' => 'Graphic Designer',
                'email' => 'design@creativeminds.com',
                'description' => 'Design engaging marketing visuals and brand kits.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 40000,
                'maxSalary' => 70000,
                'experience' => '1 year',
                'skills' => ['Photoshop', 'Illustrator'],
                'jobTypes' => ['Contract'],
                'workLocations' => ['Karachi'],
                'jobLevels' => ['Entry'],
            ],
            [
                'user_id' => 7,
                'company_id' => 2,
                'jobtitle' => 'UI/UX Designer',
                'email' => 'ux@creativeminds.com',
                'description' => 'Design wireframes and interactive mockups using Figma.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 50000,
                'maxSalary' => 85000,
                'experience' => '2 years',
                'skills' => ['Figma', 'Prototyping', 'User Research'],
                'jobTypes' => ['Remote'],
                'workLocations' => ['Islamabad'],
                'jobLevels' => ['Mid'],
            ],

            // Company 3 (id: 3)
            [
                'user_id' => 8,
                'company_id' => 3,
                'jobtitle' => 'AI Engineer',
                'email' => 'ai@nextgen.com',
                'description' => 'Work on ML models and AI-based systems.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 80000,
                'maxSalary' => 150000,
                'experience' => '4+ years',
                'skills' => ['Python', 'TensorFlow', 'Data Science'],
                'jobTypes' => ['Full-Time'],
                'workLocations' => ['Remote'],
                'jobLevels' => ['Senior'],
            ],
            [
                'user_id' => 8,
                'company_id' => 3,
                'jobtitle' => 'DevOps Engineer',
                'email' => 'devops@nextgen.com',
                'description' => 'Setup CI/CD pipelines and manage cloud infrastructure.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 70000,
                'maxSalary' => 130000,
                'experience' => '3-5 years',
                'skills' => ['AWS', 'Docker', 'CI/CD'],
                'jobTypes' => ['Hybrid'],
                'workLocations' => ['Multan'],
                'jobLevels' => ['Mid'],
            ],

            // Company 4 (id: 4)
            [
                'user_id' => 9,
                'company_id' => 4,
                'jobtitle' => 'Business Analyst',
                'email' => 'analyst@bizware.com',
                'description' => 'Analyze business requirements and document workflows.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 55000,
                'maxSalary' => 90000,
                'experience' => '2-3 years',
                'skills' => ['Documentation', 'Communication', 'JIRA'],
                'jobTypes' => ['Full-Time'],
                'workLocations' => ['Faisalabad'],
                'jobLevels' => ['Mid'],
            ],
            [
                'user_id' => 9,
                'company_id' => 4,
                'jobtitle' => 'ERP Consultant',
                'email' => 'erp@bizware.com',
                'description' => 'Consult clients on ERP modules and manage implementations.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 60000,
                'maxSalary' => 110000,
                'experience' => '3-4 years',
                'skills' => ['SAP', 'Odoo', 'ERPNext'],
                'jobTypes' => ['Onsite'],
                'workLocations' => ['Lahore'],
                'jobLevels' => ['Senior'],
            ],

            // Company 5 (id: 5)
            [
                'user_id' => 10,
                'company_id' => 5,
                'jobtitle' => 'Mobile App Developer',
                'email' => 'apps@elitesoft.com',
                'description' => 'Develop native and cross-platform apps for Android and iOS.',
                'subscribe' => false,
                'image' => null,
                'minSalary' => 65000,
                'maxSalary' => 120000,
                'experience' => '2+ years',
                'skills' => ['Flutter', 'React Native', 'iOS'],
                'jobTypes' => ['Remote', 'Contract'],
                'workLocations' => ['Sialkot'],
                'jobLevels' => ['Mid'],
            ],
            [
                'user_id' => 10,
                'company_id' => 5,
                'jobtitle' => 'API Developer',
                'email' => 'api@elitesoft.com',
                'description' => 'Build secure APIs for mobile and web applications.',
                'subscribe' => true,
                'image' => null,
                'minSalary' => 60000,
                'maxSalary' => 110000,
                'experience' => '3 years',
                'skills' => ['Node.js', 'Laravel', 'REST APIs'],
                'jobTypes' => ['Full-Time'],
                'workLocations' => ['Remote'],
                'jobLevels' => ['Senior'],
            ],
        ];

        foreach ($jobs as $data) {
            $job = Job::create([
                'user_id' => $data['user_id'],
                'company_id' => $data['company_id'],
                'jobtitle' => $data['jobtitle'],
                'email' => $data['email'],
                'description' => $data['description'],
                'subscribe' => $data['subscribe'],
                'image' => $data['image'],
                'minSalary' => $data['minSalary'],
                'maxSalary' => $data['maxSalary'],
                'experience' => $data['experience'],
                'skills' => json_encode($data['skills']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($data['jobTypes'] as $type) {
                $job->jobTypes()->create(['type' => $type]);
            }

            foreach ($data['workLocations'] as $location) {
                $job->workLocations()->create(['location' => $location]);
            }

            foreach ($data['jobLevels'] as $level) {
                $job->jobLevels()->create(['level' => $level]);
            }
        }
    }
}
