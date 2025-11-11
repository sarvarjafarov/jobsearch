<?php

namespace App\Http\Controllers;

use App\Models\JobAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobAlertController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'keyword' => ['nullable', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
        ]);

        $filters = collect($data)->map(fn ($value) => is_string($value) ? trim($value) : $value)->all();

        $alert = JobAlert::firstOrCreate(
            [
                'email' => $filters['email'],
                'keyword' => $filters['keyword'] ?? null,
                'company' => $filters['company'] ?? null,
                'location' => $filters['location'] ?? null,
            ],
            [
                'last_sent_at' => null,
            ]
        );

        $message = $alert->wasRecentlyCreated
            ? 'Thanks! Your daily job alert is live — we’ll email you each morning.'
            : 'You already have this alert, and we’ll keep emailing you daily when new roles appear.';

        return back()
            ->with('alert_status', $message)
            ->with('scroll_to', 'notify');
    }
}
