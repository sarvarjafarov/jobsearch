@php
    $experience = $resume['experience'] ?? [];
    $education = $resume['education'] ?? [];
    $skills = $resume['skills_list'] ?? [];
    $projects = $resume['projects'] ?? [];
    $links = $resume['links'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { font-family: 'Montserrat', Arial, sans-serif; font-size:11px; display:flex; }
        .sidebar { width:220px; background:#111827; color:#fff; padding:30px 20px; min-height:100vh; }
        .content { flex:1; padding:40px; }
        h2 { text-transform:uppercase; letter-spacing:0.2em; font-size:12px; margin-top:20px; color:#5F4DEE; }
        ul { margin:6px 0 0 18px; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h1 style="font-size:22px; margin:0 0 8px 0;">{{ $resume['full_name'] }}</h1>
        <p style="color:#cbd5f5;">{{ $resume['headline'] }}</p>
        <div style="font-size:10px; color:#cbd5f5;">
            <div>{{ $resume['email'] }}</div>
            @if($resume['phone'])<div>{{ $resume['phone'] }}</div>@endif
            @if($resume['location'])<div>{{ $resume['location'] }}</div>@endif
        </div>
        @if($skills)
            <h2 style="color:#fff; border-bottom:1px solid #374151; padding-bottom:6px;">Skills</h2>
            @foreach($skills as $skill)
                <p style="margin:0; color:#cbd5f5;">{{ $skill }}</p>
            @endforeach
        @endif
        @if($links)
            <h2 style="color:#fff;">Links</h2>
            @foreach($links as $link)
                <p style="margin:0;"><a href="{{ $link['url'] }}" style="color:#93c5fd;">{{ $link['label'] }}</a></p>
            @endforeach
        @endif
    </aside>

    <main class="content">
        @if($resume['summary'])
            <h2>Summary</h2>
            <p style="line-height:1.6;">{{ $resume['summary'] }}</p>
        @endif

        @if($experience)
            <h2>Experience</h2>
            @foreach($experience as $exp)
                <div style="margin-bottom:12px;">
                    <strong>{{ $exp['role'] }} — {{ $exp['company'] }}</strong>
                    <p style="margin:0; color:#6b7280;">{{ $exp['location'] }} | {{ $exp['start'] }} – {{ $exp['end'] }}</p>
                    @if($exp['bullet_points'])
                        <ul>
                            @foreach($exp['bullet_points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        @endif

        @if($projects)
            <h2>Projects</h2>
            @foreach($projects as $project)
                <div style="margin-bottom:10px;">
                    <strong>{{ $project['name'] }}</strong>
                    <ul>
                        @foreach($project['bullet_points'] as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endif

        @if($education)
            <h2>Education</h2>
            @foreach($education as $edu)
                <p><strong>{{ $edu['school'] }}</strong> — {{ $edu['degree'] }} ({{ $edu['years'] }})</p>
            @endforeach
        @endif
    </main>
</body>
</html>
