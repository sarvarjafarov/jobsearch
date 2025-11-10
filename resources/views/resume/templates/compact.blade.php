@php
    $experience = $resume['experience'] ?? [];
    $education = $resume['education'] ?? [];
    $skills = $resume['skills_list'] ?? [];
    $projects = $resume['projects'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 25px; }
        body { font-family: 'Source Sans Pro', Arial, sans-serif; font-size:10.5px; color:#1e293b; }
        h1 { font-size:22px; margin:0; }
        .two-col { display:flex; gap:18px; }
        .two-col > div { flex:1; }
        h2 { font-size:12px; color:#0ea5e9; border-bottom:1px solid #bae6fd; margin-top:14px; padding-bottom:4px; }
        ul { margin:4px 0 0 14px; }
    </style>
</head>
<body>
    <header class="two-col">
        <div>
            <h1>{{ $resume['full_name'] }}</h1>
            <p style="margin:3px 0;">{{ $resume['headline'] }}</p>
        </div>
        <div style="text-align:right; font-size:10px;">
            <div>{{ $resume['email'] }}</div>
            @if($resume['phone'])<div>{{ $resume['phone'] }}</div>@endif
            @if($resume['location'])<div>{{ $resume['location'] }}</div>@endif
        </div>
    </header>

    <div class="two-col">
        <div>
            @if($resume['summary'])
                <h2>Summary</h2>
                <p>{{ $resume['summary'] }}</p>
            @endif

            @if($experience)
                <h2>Experience</h2>
                @foreach($experience as $exp)
                    <p style="margin-bottom:2px;"><strong>{{ $exp['role'] }}</strong> · {{ $exp['company'] }}</p>
                    <p style="margin:0 0 4px 0; color:#64748b;">{{ $exp['start'] }} – {{ $exp['end'] }} · {{ $exp['location'] }}</p>
                    @if($exp['bullet_points'])
                        <ul>
                            @foreach($exp['bullet_points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            @endif
        </div>

        <div>
            @if($skills)
                <h2>Skills</h2>
                <p>{{ implode(' / ', $skills) }}</p>
            @endif

            @if($projects)
                <h2>Projects</h2>
                @foreach($projects as $project)
                    <p style="margin-bottom:2px;"><strong>{{ $project['name'] }}</strong></p>
                    @if($project['bullet_points'])
                        <ul>
                            @foreach($project['bullet_points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            @endif

            @if($education)
                <h2>Education</h2>
                @foreach($education as $edu)
                    <p style="margin-bottom:2px;"><strong>{{ $edu['school'] }}</strong></p>
                    <p style="margin:0 0 6px 0; color:#64748b;">{{ $edu['degree'] }} · {{ $edu['years'] }}</p>
                @endforeach
            @endif
        </div>
    </div>
</body>
</html>
