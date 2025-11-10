@php
    $experience = $resume['experience'] ?? [];
    $education = $resume['education'] ?? [];
    $projects = $resume['projects'] ?? [];
    $skills = $resume['skills_list'] ?? [];
    $links = $resume['links'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 40px 35px; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #111827; font-size: 12px; }
        h1 { font-size: 28px; margin: 0; }
        h2 { font-size: 16px; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.1em; color: #5F4DEE; }
        .section { margin-top: 18px; }
        .badge { display: inline-block; background: #5F4DEE; color: #fff; padding: 2px 8px; border-radius: 999px; font-size: 10px; }
        ul { margin: 4px 0 0 18px; padding: 0; }
        li { margin-bottom: 4px; }
        .grid { display:flex; justify-content: space-between; }
        .col { width: 48%; }
        .divider { height:1px; background:#E5E7EB; margin:14px 0; }
    </style>
</head>
<body>
    <div style="display:flex; justify-content: space-between; align-items:flex-start;">
        <div>
            <h1>{{ $resume['full_name'] }}</h1>
            <p class="badge">{{ $resume['headline'] }}</p>
        </div>
        <div style="text-align:right; font-size:11px; color:#4B5563;">
            <div>{{ $resume['email'] }}</div>
            @if($resume['phone'])<div>{{ $resume['phone'] }}</div>@endif
            @if($resume['location'])<div>{{ $resume['location'] }}</div>@endif
        </div>
    </div>

    @if($resume['summary'])
        <div class="section">
            <h2>Summary</h2>
            <p style="margin:0; line-height:1.5;">{{ $resume['summary'] }}</p>
        </div>
    @endif

    @if($skills)
        <div class="section">
            <h2>Skills</h2>
            <p style="margin:0;">{{ implode(' · ', $skills) }}</p>
        </div>
    @endif

    @if($experience)
        <div class="section">
            <h2>Experience</h2>
            @foreach($experience as $exp)
                <div style="margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between;">
                        <strong>{{ $exp['role'] }}</strong>
                        <span>{{ trim(($exp['start'] ?? '').' — '.($exp['end'] ?? '')) }}</span>
                    </div>
                    <div style="color:#4B5563; font-size:11px;">{{ $exp['company'] }} · {{ $exp['location'] }}</div>
                    @if($exp['bullet_points'])
                        <ul>
                            @foreach($exp['bullet_points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid">
        @if($education)
            <div class="col">
                <h2>Education</h2>
                @foreach($education as $edu)
                    <div style="margin-bottom:10px;">
                        <strong>{{ $edu['school'] }}</strong>
                        <div>{{ $edu['degree'] }}</div>
                        <div style="color:#6B7280;">{{ $edu['years'] }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($projects)
            <div class="col">
                <h2>Projects</h2>
                @foreach($projects as $project)
                    <div style="margin-bottom:10px;">
                        <strong>{{ $project['name'] }}</strong>
                        @if($project['bullet_points'])
                            <ul>
                                @foreach($project['bullet_points'] as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($links)
        <div class="divider"></div>
        <div style="font-size:11px;">
            @foreach($links as $link)
                <span style="margin-right:12px;"><strong>{{ $link['label'] }}:</strong> {{ $link['url'] }}</span>
            @endforeach
        </div>
    @endif
</body>
</html>
