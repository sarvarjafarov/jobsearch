@php
    $experience = $resume['experience'] ?? [];
    $education = $resume['education'] ?? [];
    $projects = $resume['projects'] ?? [];
    $skills = $resume['skills_list'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 35px; }
        body { font-family: 'Times New Roman', serif; color:#1f2937; font-size:12px; }
        h1 { font-size:30px; text-transform:uppercase; letter-spacing:4px; }
        h2 { font-size:15px; text-transform:uppercase; letter-spacing:0.2em; border-bottom:1px solid #9ca3af; padding-bottom:4px; margin-top:20px; }
        ul { margin:6px 0 0 16px; }
        li { margin-bottom:4px; }
    </style>
</head>
<body>
    <h1>{{ $resume['full_name'] }}</h1>
    <p style="margin:0 0 8px 0;">{{ $resume['headline'] }}</p>
    <p style="margin:0 0 12px 0; font-size:11px;">
        {{ $resume['email'] }} {{ $resume['phone'] ? ' | '.$resume['phone'] : '' }} {{ $resume['location'] ? ' | '.$resume['location'] : '' }}
    </p>

    @if($resume['summary'])
        <h2>Professional Profile</h2>
        <p style="line-height:1.6;">{{ $resume['summary'] }}</p>
    @endif

    @if($skills)
        <h2>Core Competencies</h2>
        <p>{{ implode(' | ', $skills) }}</p>
    @endif

    @if($experience)
        <h2>Experience</h2>
        @foreach($experience as $exp)
            <p style="margin-bottom:4px;"><strong>{{ $exp['role'] }}</strong>, {{ $exp['company'] }} — {{ $exp['location'] }}</p>
            <p style="margin:0 0 4px 0; color:#6b7280;">{{ $exp['start'] }} – {{ $exp['end'] }}</p>
            @if($exp['bullet_points'])
                <ul>
                    @foreach($exp['bullet_points'] as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    @endif

    @if($education)
        <h2>Education</h2>
        @foreach($education as $edu)
            <p style="margin-bottom:2px;"><strong>{{ $edu['degree'] }}</strong> — {{ $edu['school'] }}</p>
            <p style="margin:0 0 6px 0; color:#6b7280;">{{ $edu['years'] }}</p>
        @endforeach
    @endif

    @if($projects)
        <h2>Selected Projects</h2>
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
</body>
</html>
