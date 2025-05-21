<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'user_id' => 6,
                'company_name' => 'Tech Vision Ltd.',
                'contact_no' => '03001234567',
                'company_email' => 'info@techvision.com',
                'company_foundation_date' => '2010-05-20',
                'services' => json_encode(['Web Development', 'SEO', 'Backend APIs', 'Laravel Solutions', 'E-Commerce']),
                'company_location' => 'Lahore, Pakistan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'company_name' => 'Creative Minds Inc.',
                'contact_no' => '03111234567',
                'company_email' => 'hello@creativeminds.com',
                'company_foundation_date' => '2015-08-10',
                'services' => json_encode(['Mobile App Development', 'iOS/Android Apps', 'Flutter Development', 'Bug Fixing', 'Play Store Deployment']),
                'company_location' => 'Karachi, Pakistan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'company_name' => 'NextGen Solutions',
                'contact_no' => '03221234567',
                'company_email' => 'contact@nextgensolutions.com',
                'company_foundation_date' => '2018-03-15',
                'services' => json_encode(['UI/UX Design', 'Figma to HTML', 'Responsive Design', 'Wireframing', 'Brand Identity']),
                'company_location' => 'Islamabad, Pakistan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'company_name' => 'BizWare Systems',
                'contact_no' => '03451234567',
                'company_email' => 'support@bizware.com',
                'company_foundation_date' => '2012-11-01',
                'services' => json_encode(['Digital Marketing', 'Facebook Ads', 'Google Ads', 'Email Campaigns', 'Content Strategy']),
                'company_location' => 'Faisalabad, Pakistan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'company_name' => 'Elite Softwares',
                'contact_no' => '03561234567',
                'company_email' => 'elite@softwares.com',
                'company_foundation_date' => '2019-07-07',
                'services' => json_encode(['Cloud Hosting', 'AWS Integration', 'CI/CD Pipelines', 'Docker Setup', 'Server Maintenance']),
                'company_location' => 'Multan, Pakistan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('companies')->insert($companies);
    }
}
