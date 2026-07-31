<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo.meta', [
        'seoTitle' => $subdivision->schoolname . ' - Poker Exams',
        'seoDescription' => 'Free practice quizzes for ' . $subdivision->schoolname . '. Study with instant rationales.',
        'seoCanonical' => $canonical,
        'seoOgTitle' => $subdivision->schoolname . ' Practice Tests',
        'seoOgDescription' => 'Free practice quizzes for ' . $subdivision->schoolname . '.',
    ])

    @include('partials.seo.assets')
    <script src="{{ asset('js/core.js') }}" defer></script>

    @include('partials.schema.breadcrumb-list', [
        'pageUrl' => $canonical,
        'pageName' => $subdivision->schoolname,
        'pageDescription' => 'Free practice quizzes for ' . $subdivision->schoolname . '.',
        'breadcrumbItems' => [
            ['name' => 'Home', 'url' => route('library.index')],
            ['name' => $subdivision->schoolname, 'url' => $canonical],
        ],
    ])

    @php
        $explorerSchool = ($sidebarSchools ?? collect())->firstWhere('slug', $subdivision->slug);
        $subjectListItems = [];

        if ($explorerSchool) {
            foreach ($explorerSchool->courses as $course) {
                foreach ($course->exams ?? [] as $exam) {
                    $subjectListItems[] = [
                        'name' => $exam->title,
                        'url' => exam_url($subdivision->slug, $exam->slug, $course->slug),
                    ];
                }
            }
        }
    @endphp

    @include('partials.schema.collection-page', [
        'pageName' => $subdivision->schoolname,
        'pageDescription' => 'Free practice quizzes for ' . $subdivision->schoolname . '.',
        'pageUrl' => $canonical,
        'listName' => $subdivision->schoolname . ' quizzes',
        'listItems' => $subjectListItems,
    ])
</head>

<body>
    <div class="page-bg">
        @include('partials.header')

        @include('partials.sidebar', [
            'showCourseCount' => true,
            'activeGroup' => $activeGroup ?? null,
            'activeSubdivisionSlug' => $subdivision->slug ?? null,
        ])

        <main class="main-content-wrapper">
            <div class="main-content-inner">
                <div class="top-nav-bar">
                    <nav class="course-breadcrumbs" aria-label="Breadcrumb">
                        <a class="crumb-link" href="{{ route('library.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                                <path
                                    d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                                </path>
                            </svg>
                            <span class="crumb-text">Home</span>
                        </a>
                        <svg class="crumb-separator" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <span class="crumb-current" aria-current="page">{{ $subdivision->schoolname }}</span>
                    </nav>
                </div>

                @php
                    $explorerThemeIndex = ($sidebarSchools ?? collect())->search(
                        fn($school) => $school->slug === $subdivision->slug
                    );
                    if ($explorerThemeIndex === false) {
                        $explorerThemeIndex = 0;
                    }
                @endphp

                @if ($explorerSchool)
                    @include('partials.school-explorer', [
                        'school' => $explorerSchool,
                        'themeIndex' => $explorerThemeIndex,
                        'activeCourseSlug' => null,
                    ])
                @else
                    <div class="school-explorer theme-college">
                        <p class="course-desc" style="padding: 1.5rem;">No courses are available for this category yet.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    @include('partials.whatsapp-widget')

</body>

</html>