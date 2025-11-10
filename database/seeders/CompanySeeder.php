<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Aurora Labs',
                'industry' => 'Productivity Software',
                'headquarters' => 'Remote • North America',
                'size' => '201-500 employees',
                'website_url' => 'https://auroralabs.com',
                'logo_url' => 'https://placehold.co/96x96?text=AL',
                'founded_year' => 2016,
                'rating' => 4.7,
                'description' => 'Aurora builds an AI-native workspace for hybrid teams. We obsess over craft, ship quickly, and default to autonomy.',
                'perks' => ['Remote-first', 'Equity & 401(k)', 'Flexible PTO'],
            ],
            [
                'name' => 'Pulse Analytics',
                'industry' => 'Data & AI',
                'headquarters' => 'Toronto, Canada',
                'size' => '501-1,000 employees',
                'website_url' => 'https://pulseanalytics.io',
                'logo_url' => 'https://placehold.co/96x96?text=PA',
                'founded_year' => 2014,
                'rating' => 4.5,
                'description' => 'Pulse turns messy operational data into insights for climate-friendly businesses.',
                'perks' => ['Hybrid office', 'Learning budget', 'Wellness stipend'],
            ],
            [
                'name' => 'Nimbus',
                'industry' => 'Fintech',
                'headquarters' => 'Remote • Europe',
                'size' => '101-200 employees',
                'website_url' => 'https://nimbus.finance',
                'logo_url' => 'https://placehold.co/96x96?text=NB',
                'founded_year' => 2018,
                'rating' => 4.2,
                'description' => 'Nimbus helps SaaS companies automate billing and revenue operations with friendly workflows.',
                'perks' => ['Quarterly retreats', 'Home office budget'],
            ],
            [
                'name' => 'Beacon AI',
                'industry' => 'Aviation Technology',
                'headquarters' => 'Austin, TX',
                'size' => '51-200 employees',
                'website_url' => 'https://beacon.ai',
                'logo_url' => 'https://placehold.co/96x96?text=BAI',
                'founded_year' => 2019,
                'rating' => 4.4,
                'description' => 'Beacon builds flight ops software that keeps pilots, dispatch, and maintenance aligned in real time.',
                'perks' => ['Onsite lab', 'Family health plans'],
            ],
            [
                'name' => 'SimplifiPay',
                'industry' => 'Payments',
                'headquarters' => 'New York, NY',
                'size' => '501-1,000 employees',
                'website_url' => 'https://simplifipay.com',
                'logo_url' => 'https://placehold.co/96x96?text=SP',
                'founded_year' => 2012,
                'rating' => 4.1,
                'description' => 'SimplifiPay powers the checkout for ambitious retailers across the globe.',
                'perks' => ['Gym reimbursement', 'Commuter benefits'],
            ],
            [
                'name' => 'Lumen Health',
                'industry' => 'Digital Health',
                'headquarters' => 'Remote',
                'size' => '201-500 employees',
                'website_url' => 'https://lumenhealth.com',
                'logo_url' => 'https://placehold.co/96x96?text=LH',
                'founded_year' => 2015,
                'rating' => 4.6,
                'description' => 'Lumen pairs clinicians with data scientists to deliver proactive care plans.',
                'perks' => ['Monthly wellness day', 'Parental leave'],
            ],
            [
                'name' => 'ParcelFlow',
                'industry' => 'Logistics',
                'headquarters' => 'Denver, CO',
                'size' => '201-500 employees',
                'website_url' => 'https://parcelflow.co',
                'logo_url' => 'https://placehold.co/96x96?text=PF',
                'founded_year' => 2013,
                'rating' => 4.0,
                'description' => 'ParcelFlow orchestrates freight operations for modern commerce brands.',
                'perks' => ['Stock options', 'Tuition reimbursement'],
            ],
            [
                'name' => 'Cobalt Vision',
                'industry' => 'Computer Vision',
                'headquarters' => 'San Francisco, CA',
                'size' => '51-200 employees',
                'website_url' => 'https://cobaltvision.com',
                'logo_url' => 'https://placehold.co/96x96?text=CV',
                'founded_year' => 2020,
                'rating' => 4.3,
                'description' => 'Cobalt Vision delivers perception stacks for robotics and mixed-reality devices.',
                'perks' => ['Hardware stipend', 'Equity'],
            ],
            [
                'name' => 'Evergreen',
                'industry' => 'Climate Tech',
                'headquarters' => 'Seattle, WA',
                'size' => '101-200 employees',
                'website_url' => 'https://evergreen.work',
                'logo_url' => 'https://placehold.co/96x96?text=EG',
                'founded_year' => 2017,
                'rating' => 4.8,
                'description' => 'Evergreen finances and deploys decarbonization projects for Fortune 500 campuses.',
                'perks' => ['Impact bonus', 'Volunteer PTO'],
            ],
        ];

        foreach ($companies as $data) {
            $payload = array_merge($data, [
                'slug' => Str::slug($data['name']),
            ]);

            Company::updateOrCreate(['name' => $data['name']], $payload);
        }
    }
}
