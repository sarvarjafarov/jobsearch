<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->string('q')->toString();
        $industry = $request->string('industry')->toString();

        $companies = Company::withCount(['publishedJobs'])
            ->whereHas('publishedJobs')
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('headquarters', 'like', "%{$query}%");
                });
            })
            ->when($industry, fn ($builder) => $builder->where('industry', $industry))
            ->orderByDesc('published_jobs_count')
            ->paginate(12)
            ->withQueryString();

        $industries = Company::query()
            ->whereNotNull('industry')
            ->select('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        return view('companies.index', [
            'companies' => $companies,
            'industries' => $industries,
            'query' => $query,
            'selectedIndustry' => $industry,
        ]);
    }

    public function show(Company $company)
    {
        $company->load(['publishedJobs' => fn ($q) => $q->orderByDesc('published_date')]);

        $reviewQuery = $company->reviews();
        $reviewCount = (clone $reviewQuery)->count();
        $recommendRate = $reviewCount > 0
            ? round(((clone $reviewQuery)->where('would_recommend', true)->count() / $reviewCount) * 100)
            : null;

        $averages = (clone $reviewQuery)->selectRaw(
            'AVG(overall_rating) as overall,
             AVG(culture_rating) as culture,
             AVG(compensation_rating) as compensation,
             AVG(leadership_rating) as leadership,
             AVG(work_life_rating) as work_life,
             AVG(growth_rating) as growth'
        )->first();

        $reviews = (clone $reviewQuery)->latest()->paginate(5);

        return view('companies.show', [
            'company' => $company,
            'averages' => $averages,
            'reviewCount' => $reviewCount,
            'recommendRate' => $recommendRate,
            'reviews' => $reviews,
        ]);
    }

    public function storeReview(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reviewer_name' => ['nullable', 'string', 'max:120'],
            'reviewer_role' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['nullable', 'string', 'max:120'],
            'culture_rating' => ['required', 'integer', 'between:1,5'],
            'compensation_rating' => ['required', 'integer', 'between:1,5'],
            'leadership_rating' => ['required', 'integer', 'between:1,5'],
            'work_life_rating' => ['required', 'integer', 'between:1,5'],
            'growth_rating' => ['required', 'integer', 'between:1,5'],
            'overall_rating' => ['required', 'integer', 'between:1,5'],
            'highlights' => ['nullable', 'string', 'max:2000'],
            'challenges' => ['nullable', 'string', 'max:2000'],
            'advice' => ['nullable', 'string', 'max:2000'],
            'would_recommend' => ['nullable', 'boolean'],
        ]);

        $company->reviews()->create(array_merge($validated, [
            'would_recommend' => $request->boolean('would_recommend'),
        ]));

        $company->refreshAggregatedRating();

        return redirect()
            ->to(route('companies.show', $company).'#reviews')
            ->with('status', 'Thanks for sharing your experience!');
    }
}
