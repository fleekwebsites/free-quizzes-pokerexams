<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($course->coursename ?? $subdivision->schoolname) }} - Poker Exams</title>
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="description"
        content="Free practice quizzes for {{ $course->coursename ?? $subdivision->schoolname ?? 'this category' }}. Start studying with instant rationales.">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:title" content="{{ ($course->coursename ?? $subdivision->schoolname) }} Practice Tests">
    <meta property="og:description"
        content="Free practice quizzes for {{ $course->coursename ?? $subdivision->schoolname }}.">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="{{ asset('css/inter-font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/library.css') }}" rel="stylesheet">
    <script src="{{ asset('js/core.js') }}" defer></script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ route('library.index') }}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "{{ $subdivision->schoolname }}",
          "item": "{{ $canonical }}"
        }
      ]
    }
    </script>
</head>

<body>
    <div class="page-bg">
        @include('partials.header')

        @include('partials.sidebar', [
            'activeGroup' => $activeGroup ?? null,
            'activeSubdivisionSlug' => $subdivision->slug ?? null,
            'activeCourseSlug' => $activeCourseSlug ?? ($course->slug ?? null),
        ])

        <main class="main-content-wrapper">
            <div class="main-content-inner">
                <div class="top-nav-bar">
                    <nav class="course-breadcrumbs">
                        <a class="crumb-link" href="{{ route('library.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                                <path
                                    d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                                </path>
                            </svg>
                            <span class="crumb-text">Home</span>
                        </a>
                        <svg class="crumb-separator" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <a class="crumb-link" href="{{ route('library.index') }}">
                            <span class="crumb-text">Library</span>
                        </a>
                        <svg class="crumb-separator" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <a class="crumb-link" href="{{ route('subdivision.show', $subdivision) }}">
                            <span class="crumb-text">{{ $subdivision->schoolname }}</span>
                        </a>
                        @if (! empty($course))
                            <svg class="crumb-separator" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <span class="crumb-current">{{ $course->coursename }}</span>
                        @else
                            <svg class="crumb-separator" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <span class="crumb-current">{{ $subdivision->schoolname }}</span>
                        @endif
                    </nav>
                </div>

                <div class="course-hero-card">
                    <div class="course-hero-inner">
                        <div class="course-hero-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 7v14"></path>
                                <path
                                    d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z">
                                </path>
                            </svg>
                        </div>
                        <div class="course-hero-text">
                            <h1 class="course-title">{{ $course->coursename ?? $subdivision->schoolname }}</h1>
                            <p class="course-stats">{{ $exams->sum('question_count') }} free questions · {{ $exams->count() }} {{ Str::plural('quiz', $exams->count()) }}</p>
                            <p class="course-desc">Practice with free quizzes for {{ $course->coursename ?? $subdivision->schoolname }}. Each quiz includes detailed rationales.</p>
                        </div>
                    </div>
                </div>

                @php
                    $themeClasses = ['theme-blue', 'theme-violet', 'theme-emerald', 'theme-amber', 'theme-rose', 'theme-cyan'];
                    $groupIndex = 0;
                @endphp

                @forelse ($examGroups as $subjectName => $groupExams)
                    @php $theme = $themeClasses[$groupIndex % count($themeClasses)]; $groupIndex++; @endphp
                    <div class="subject-group {{ $theme }}">
                        <div class="subject-header">
                            <div class="subject-accent-line"></div>
                            <div class="subject-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                </svg>
                            </div>
                            <h2 class="subject-title">{{ $subjectName }}</h2>
                            <span class="subject-badge">{{ $groupExams->sum('question_count') }} Qs · {{ $groupExams->count() }} {{ Str::plural('quiz', $groupExams->count()) }}</span>
                        </div>
                        <div class="subject-body">
                            <div class="quiz-grid">
                                @foreach ($groupExams as $exam)
                                    <a class="quiz-card"
                                        href="{{ exam_url($subdivision->slug, $exam->slug, $course->slug ?? null) }}">
                                        <div class="quiz-card-top">
                                            <h3 class="quiz-title">{{ $exam->title }}</h3>
                                        </div>
                                        <div class="quiz-card-bottom">
                                            <div class="quiz-metrics">
                                                <span class="metric-qcount">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                        <path d="M10 9H8"></path>
                                                        <path d="M16 13H8"></path>
                                                        <path d="M16 17H8"></path>
                                                    </svg>{{ $exam->question_count }} Qs
                                                </span>
                                                <span class="metric-diff diff-medium">free</span>
                                            </div>
                                            <span class="btn-start">Start
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5 12h14"></path>
                                                    <path d="m12 5 7 7-7 7"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="subject-group theme-blue">
                        <div class="subject-body">
                            <p class="course-desc" style="padding: 1.5rem;">No free quizzes are available for this category yet. Check back soon.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <a href="https://api.whatsapp.com/send/?phone=15107718152&amp;text=Hello&amp;type=phone_number&amp;app_absent=0"
        class="wa-floating-widget" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24">
            <path fill="currentColor"
                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157.1zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z">
            </path>
        </svg>
        <span class="wa-text">Chat on WhatsApp</span>
    </a>

</body>

</html>
