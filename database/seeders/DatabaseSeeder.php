<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
     public function run()
    {
        // Candidates (no company)
        User::create([
            'name' => 'Alice Smith',
            'role' => 'candidate',
            'email' => 'alice@example.com',
            'password' => Hash::make('candidatepass'),
        ]);

        User::create([
            'name' => 'Bob Johnson',
            'role' => 'candidate',
            'email' => 'bob@example.com',
            'password' => Hash::make('candidatepass'),
        ]);

        User::create([
            'name' => 'Carol Williams',
            'role' => 'candidate',
            'email' => 'carol@example.com',
            'password' => Hash::make('candidatepass'),
        ]);

        User::create([
            'name' => 'David Brown',
            'role' => 'candidate',
            'email' => 'david@example.com',
            'password' => Hash::make('candidatepass'),
        ]);

        User::create([
            'name' => 'Eva Davis',
            'role' => 'candidate',
            'email' => 'eva@example.com',
            'password' => Hash::make('candidatepass'),
        ]);

        // Recruiters with companies

        $recruiter1 = User::create([
            'name' => 'Frank Miller',
            'role' => 'recruiter',
            'email' => 'frank@example.com',
            'password' => Hash::make('recruiterpass'),
        ]);

        Company::create([
            'user_id' => $recruiter1->id,
            'company_name' => 'Miller Recruiting',
            'contact_no' => '555-1001',
            'company_email' => 'contact@millerrecruiting.com',
            'company_foundation_date' => '2015-04-12',
            'services' => json_encode(['Tech Hiring', 'Consulting']),
            'company_location' => '100 Market Street',
        ]);

        $recruiter2 = User::create([
            'name' => 'Grace Lee',
            'role' => 'recruiter',
            'email' => 'grace@example.com',
            'password' => Hash::make('recruiterpass'),
        ]);

        Company::create([
            'user_id' => $recruiter2->id,
            'company_name' => 'Lee Talent Solutions',
            'contact_no' => '555-1002',
            'company_email' => 'info@leetalent.com',
            'company_foundation_date' => '2012-09-03',
            'services' => json_encode(['Finance Recruitment']),
            'company_location' => '200 Main Avenue',
        ]);

        $recruiter3 = User::create([
            'name' => 'Henry Wilson',
            'role' => 'recruiter',
            'email' => 'henry@example.com',
            'password' => Hash::make('recruiterpass'),
        ]);

        Company::create([
            'user_id' => $recruiter3->id,
            'company_name' => 'Wilson Careers',
            'contact_no' => '555-1003',
            'company_email' => 'hello@wilsoncareers.com',
            'company_foundation_date' => '2018-01-20',
            'services' => json_encode(['Healthcare Staffing']),
            'company_location' => '300 Broadway Blvd',
        ]);

        $recruiter4 = User::create([
            'name' => 'Isabel Clark',
            'role' => 'recruiter',
            'email' => 'isabel@example.com',
            'password' => Hash::make('recruiterpass'),
        ]);

        Company::create([
            'user_id' => $recruiter4->id,
            'company_name' => 'Clark HR Services',
            'contact_no' => '555-1004',
            'company_email' => 'contact@clarkhr.com',
            'company_foundation_date' => '2010-11-11',
            'services' => json_encode(['Executive Search']),
            'company_location' => '400 Park Lane',
        ]);

        $recruiter5 = User::create([
            'name' => 'Jackie Martinez',
            'role' => 'recruiter',
            'email' => 'jackie@example.com',
            'password' => Hash::make('recruiterpass'),
        ]);

        Company::create([
            'user_id' => $recruiter5->id,
            'company_name' => 'Martinez Staffing',
            'contact_no' => '555-1005',
            'company_email' => 'jobs@martinezstaffing.com',
            'company_foundation_date' => '2013-06-30',
            'services' => json_encode(['General Staffing']),
            'company_location' => '500 Central Ave',
        ]);
    }
}
