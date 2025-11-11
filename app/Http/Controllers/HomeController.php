<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a searchable list of all jobs.
     */
    public function index(Request $request)
    {
        $query = $request->string('q')->toString();
        $companyFilter = $request->string('company')->toString();
        $order = $request->input('sort', 'latest');

        $jobsQuery = Job::query()
            ->with('companyProfile')
            ->published()
            ->when($query, function ($builder) use ($query) {
                return $builder->where(function ($sub) use ($query) {
                    $sub->where('position', 'like', "%{$query}%")
                        ->orWhere('company', 'like', "%{$query}%");
                });
            })
            ->when($companyFilter, fn ($builder) => $builder->where('company', $companyFilter))
            ->when($order === 'soonest', fn ($builder) => $builder->orderBy('deadline_date'))
            ->when($order !== 'soonest', fn ($builder) => $builder->orderByDesc('published_date'));

        $jobs = $jobsQuery->paginate(20)->withQueryString();

        $companies = Job::query()
            ->select('company')
            ->published()
            ->distinct()
            ->orderBy('company')
            ->pluck('company')
            ->filter()
            ->values();

        $totalJobs = Job::published()->count();

        $latestPosts = Post::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('home', [
            'jobs' => $jobs,
            'query' => $query,
            'companies' => $companies,
            'selectedCompany' => $companyFilter,
            'selectedSort' => $order,
            'totalJobs' => $totalJobs,
            'latestPosts' => $latestPosts,
        ]);
    }
}
