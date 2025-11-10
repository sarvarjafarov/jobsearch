<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a searchable list of all jobs.
     */
    public function index(Request $request)
    {
        $query = $request->string('q')->toString();

        $jobs = Job::query()
            ->with('companyProfile')
            ->published()
            ->when($query, function ($builder) use ($query) {
                return $builder->where(function ($sub) use ($query) {
                    $sub->where('position', 'like', "%{$query}%")
                        ->orWhere('company', 'like', "%{$query}%");
                });
            })
            ->orderByDesc('published_date')
            ->get();

        return view('home', [
            'jobs' => $jobs,
            'query' => $query,
        ]);
    }
}
