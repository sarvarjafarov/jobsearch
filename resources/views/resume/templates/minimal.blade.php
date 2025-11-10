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
        @page { margin: 30px; }
        body { font-family: 'Inter', Arial, sans-serif; font-size:11px; color:#0f172a; }
        .grid { display:flex; gap:20px; }
        .sidebar { width:30%; background:#f8fafc; padding:18px; border-radius:18px; }
        .content { flex:1; }
        h2 { text-transform:uppercase; letter-spacing:0.2em; font-size:12px; margin-top:18px; color:#94a3b8; }
        ul { padding-left:18px; margin:6px 0; }
    </style>
</head>
<body>
    <div class="grid">
        <aside class="sidebar">
            <h1 style="margin:0; font-size:24px;">{{ $resume['full_name'] }}</h1>
            <p style="color:#475569;">{{ $resume['headline'] }}</p>
            <div style="font-size:10px; color:#475569;">
                <div>{{ $resume['email'] }}</div>
                @if($resume['phone'])<div>{{ $resume['phone'] }}</div>@endif
                @if($resume['location'])<div>{{ $resume['location'] }}</div>@endif
            </div>

            @if($skills)
                <h2>Skills</h2>
                <ul>
                    @foreach($skills as $skill)
                        <li>{{ $skill }}</li>
                    @endforeach
                </ul>
            @endif

            @if($projects)
                <h2>Projects</h2>
                @foreach($projects as $project)
                    <p style="margin-bottom:6px;"><strong>{{ $project['name'] }}</strong><br>
                        {!! implode('<br>', $project['bullet_points']) !!}</p>
                @endforeach
            @endif
        </aside>

        <main class="content">
            @if($resume['summary'])
                <h2>Profile</h2>
                <p style="line-height:1.6;">{{ $resume['summary'] }}</p>
            @endif

            @if($experience)
                <h2>Experience</h2>
                @foreach($experience as $exp)
                    <div style="margin-bottom:12px;">
                        <div style="display:flex; justify-content:space-between;">
                            <strong>{{ $exp['role'] }}</strong>
                            <span>{{ $exp['start'] }} – {{ $exp['end'] }}</span>
                        </div>
                        <p style="margin:0; color:#475569;">{{ $exp['company'] }} · {{ $exp['location'] }}</p>
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

            @if($education)
                <h2>Education</h2>
                @foreach($education as $edu)
                    <p><strong>{{ $edu['school'] }}</strong> — {{ $edu['degree'] }} ({{ $edu['years'] }})</p>
                @endforeach
            @endif
        </main>
    </div>
</body>
</html>
