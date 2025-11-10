<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Show the job creation form.
     */
    public function create()
    {
        return view('post-job');
    }

    /**
     * Persist a newly submitted job to storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'published_date' => ['required', 'date'],
            'deadline_date' => ['required', 'date', 'after_or_equal:published_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'apply_url' => ['required', 'url', 'max:2048'],
        ]);

        $company = Company::firstOrCreate(
            ['name' => $validated['company']],
            [
                'industry' => null,
                'headquarters' => $validated['location'] ?: 'Remote',
                'size' => null,
                'website_url' => null,
            ]
        );

        $validated['company'] = $company->name;

        Job::create(array_merge($validated, [
            'status' => Job::STATUS_PENDING,
            'company_id' => $company->id,
        ]));

        return redirect()
            ->route('home')
            ->with('status', 'Thanks! Your job has been submitted for review and will go live once approved.');
    }

    /**
     * Display a single job detail page.
     */
    public function show(Job $job)
    {
        abort_unless($job->status === Job::STATUS_PUBLISHED, 404);

        $job->load('companyProfile');

        return view('job-detail', ['job' => $job]);
    }
}
