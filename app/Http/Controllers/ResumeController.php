<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    /**
     * Available resume template keys and labels.
     */
    private array $templates = [
        'modern' => 'Modern Gradient',
        'classic' => 'Classic Professional',
        'minimal' => 'Minimal Crisp',
        'bold' => 'Bold Sidebar',
        'compact' => 'Compact Columns',
    ];

    public function form()
    {
        return view('resume.form', [
            'templates' => $this->templates,
        ]);
    }

    public function generate(Request $request)
    {
        $template = $request->string('template')->toString();
        abort_unless(array_key_exists($template, $this->templates), 404);

        $resume = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'experience' => ['array'],
            'experience.*.role' => ['nullable', 'string', 'max:255'],
            'experience.*.company' => ['nullable', 'string', 'max:255'],
            'experience.*.location' => ['nullable', 'string', 'max:255'],
            'experience.*.start' => ['nullable', 'string', 'max:255'],
            'experience.*.end' => ['nullable', 'string', 'max:255'],
            'experience.*.details' => ['nullable', 'string', 'max:2000'],
            'education' => ['array'],
            'education.*.school' => ['nullable', 'string', 'max:255'],
            'education.*.degree' => ['nullable', 'string', 'max:255'],
            'education.*.years' => ['nullable', 'string', 'max:255'],
            'projects' => ['array'],
            'projects.*.name' => ['nullable', 'string', 'max:255'],
            'projects.*.description' => ['nullable', 'string', 'max:2000'],
            'links' => ['array'],
            'links.*.label' => ['nullable', 'string', 'max:255'],
            'links.*.url' => ['nullable', 'url', 'max:255'],
        ]);

        $normalized = $this->normalizeResumeData($resume);

        $pdf = Pdf::loadView("resume.templates.$template", [
            'resume' => $normalized,
            'templateName' => $this->templates[$template],
        ])->setPaper('a4');

        $fileName = Str::slug($resume['full_name'].'-resume').'.pdf';

        return $pdf->download($fileName);
    }

    private function normalizeResumeData(array $resume): array
    {
        $resume['skills_list'] = collect(preg_split('/[\n,]+/', Arr::get($resume, 'skills', '')))
            ->map(fn ($skill) => trim($skill))
            ->filter()
            ->values()
            ->all();

        $resume['experience'] = collect($resume['experience'] ?? [])
            ->filter(fn ($exp) => filled($exp['role'] ?? null) || filled($exp['company'] ?? null))
            ->map(function ($exp) {
                $exp['bullet_points'] = collect(preg_split('/\r\n|\r|\n/', $exp['details'] ?? ''))
                    ->map(fn ($line) => trim(ltrim($line, '-• ')))
                    ->filter()
                    ->values()
                    ->all();
                return $exp;
            })
            ->values()
            ->all();

        $resume['education'] = collect($resume['education'] ?? [])
            ->filter(fn ($edu) => filled($edu['school'] ?? null))
            ->values()
            ->all();

        $resume['projects'] = collect($resume['projects'] ?? [])
            ->filter(fn ($project) => filled($project['name'] ?? null))
            ->map(function ($project) {
                $project['bullet_points'] = collect(preg_split('/\r\n|\r|\n/', $project['description'] ?? ''))
                    ->map(fn ($line) => trim(ltrim($line, '-• ')))
                    ->filter()
                    ->values()
                    ->all();
                return $project;
            })
            ->values()
            ->all();

        $resume['links'] = collect($resume['links'] ?? [])
            ->filter(fn ($link) => filled($link['label'] ?? null) && filled($link['url'] ?? null))
            ->values()
            ->all();

        return $resume;
    }
}
