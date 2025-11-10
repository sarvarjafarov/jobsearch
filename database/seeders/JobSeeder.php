<?php

namespace Database\Seeders;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'position' => 'Product Designer',
                'company' => 'Aurora Labs',
                'description' => 'Lead end-to-end product design initiatives, partnering with PM and engineering to ship elegant experiences for our productivity suite.',
                'published_date' => Carbon::now()->subDays(1),
                'deadline_date' => Carbon::now()->addDays(20),
                'location' => 'Remote - North America',
                'apply_url' => 'https://careers.auroralabs.com/product-designer',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Software Engineer, Backend',
                'company' => 'Pulse Analytics',
                'description' => 'Own key microservices within our data platform. Work with Go, Laravel, and PostgreSQL to deliver resilient APIs.',
                'published_date' => Carbon::now()->subDays(2),
                'deadline_date' => Carbon::now()->addDays(25),
                'location' => 'Toronto, Canada',
                'apply_url' => 'https://pulseanalytics.com/jobs/backend-engineer',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Growth Marketing Manager',
                'company' => 'Nimbus',
                'description' => 'Experiment with paid channels, lifecycle campaigns, and landing experiences to accelerate self-serve revenue.',
                'published_date' => Carbon::now()->subDays(3),
                'deadline_date' => Carbon::now()->addDays(15),
                'location' => 'Remote - Europe',
                'apply_url' => 'https://jobs.nimbus.io/growth-marketing-manager',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Customer Success Lead',
                'company' => 'Beacon AI',
                'description' => 'Develop playbooks, onboard enterprise accounts, and champion customer outcomes across the company.',
                'published_date' => Carbon::now()->subDays(4),
                'deadline_date' => Carbon::now()->addDays(30),
                'location' => 'Austin, TX',
                'apply_url' => 'https://beacon.ai/careers/customer-success-lead',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Frontend Engineer',
                'company' => 'SimplifiPay',
                'description' => 'Ship delightful product surfaces using Vue, Tailwind CSS, and Storybook. Collaborate closely with product designers.',
                'published_date' => Carbon::now()->subDays(5),
                'deadline_date' => Carbon::now()->addDays(18),
                'location' => 'New York, NY (Hybrid)',
                'apply_url' => 'https://simplifipay.com/jobs/frontend-engineer',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Data Scientist',
                'company' => 'Lumen Health',
                'description' => 'Explore patient datasets, build predictive models, and partner with clinicians to ship meaningful insights.',
                'published_date' => Carbon::now()->subDays(6),
                'deadline_date' => Carbon::now()->addDays(40),
                'location' => 'Remote',
                'apply_url' => 'https://careers.lumenhealth.com/data-scientist',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Operations Manager',
                'company' => 'ParcelFlow',
                'description' => 'Oversee logistics programs across multiple fulfillment centers while implementing continuous improvements.',
                'published_date' => Carbon::now()->subDays(7),
                'deadline_date' => Carbon::now()->addDays(22),
                'location' => 'Denver, CO',
                'apply_url' => 'https://parcelflow.co/jobs/operations-manager',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Machine Learning Engineer',
                'company' => 'Cobalt Vision',
                'description' => 'Prototype and productionize ML models that power our real-time image understanding stack.',
                'published_date' => Carbon::now()->subDays(8),
                'deadline_date' => Carbon::now()->addDays(28),
                'location' => 'San Francisco, CA (Hybrid)',
                'apply_url' => 'https://cobaltvision.com/careers/ml-engineer',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'People Operations Partner',
                'company' => 'Evergreen',
                'description' => 'Own onboarding, performance, and culture programs for a fast-growing climate tech startup.',
                'published_date' => Carbon::now()->subDays(9),
                'deadline_date' => Carbon::now()->addDays(35),
                'location' => 'Seattle, WA',
                'apply_url' => 'https://evergreen.work/jobs/people-ops-partner',
                'status' => Job::STATUS_PUBLISHED,
            ],
            [
                'position' => 'Sales Development Representative',
                'company' => 'Flux Security',
                'description' => 'Source and qualify leads for our enterprise security platform while partnering with AEs to close deals.',
                'published_date' => Carbon::now()->subDays(10),
                'deadline_date' => Carbon::now()->addDays(12),
                'location' => 'Remote - US',
                'apply_url' => 'https://fluxsecurity.com/careers/sdr',
                'status' => Job::STATUS_PUBLISHED,
            ],
        ];

        Job::insert(array_map(function ($job) {
            $job['created_at'] = now();
            $job['updated_at'] = now();
            return $job;
        }, $jobs));
    }
}
