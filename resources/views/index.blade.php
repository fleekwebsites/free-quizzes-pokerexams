<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo.meta', [
        'seoTitle' => 'Free Practice Exams & Quizzes | Poker Exams',
        'seoDescription' => 'Browse free practice quizzes by certification category. Pick a school and start a quiz.',
        'seoCanonical' => $canonical ?? route('library.index'),
        'seoOgTitle' => 'Free Practice Exams | Poker Exams',
        'seoOgDescription' => 'Explore free practice quizzes and certification exam prep by category.',
    ])

    @include('partials.seo.assets')
    <script src="{{ asset('js/core.js') }}" defer></script>

    @include('partials.schema.website')

    @php
        $indexListItems = ($sidebarSchools ?? collect())->map(function ($school) {
            return [
                'name' => $school->schoolname,
                'url' => route('subdivision.show', $school->slug),
            ];
        })->all();
    @endphp

    @include('partials.schema.collection-page', [
        'pageName' => 'Practice Exams',
        'pageDescription' => 'Browse free practice quizzes across ' . ($sidebarSchools ?? collect())->count() . ' certification categories.',
        'pageUrl' => $canonical ?? route('library.index'),
        'listName' => 'Exam categories',
        'listItems' => $indexListItems,
    ])
</head>

<body>
    <div class="page-bg">
        @include('partials.header', ['showSearch' => true])

        @include('partials.sidebar', ['showCourseCount' => true])

        <main class="main-content-wrapper">
            <div class="main-content-inner">
                <div class="content-spacing">
                    <section class="hero-section">
                        <div class="hero-header">
                            <div>
                                <h1 class="hero-title">Practice Exams</h1>
                                <p class="hero-subtitle">{{ ($sidebarSchools ?? collect())->count() }} Categories ·
                                    {{ $totalCourses ?? 0 }} Courses
                                </p>
                            </div>
                        </div>
                        <div class="hero-search" id="heroSearchWrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            <input type="search" id="heroSearchInput" class="hero-search-input"
                                placeholder="Search exams or quizzes…" autocomplete="off"
                                aria-label="Search exams or quizzes">

                            <div id="heroSearchDropdown" class="pe-search-dropdown">
                                <div id="heroSearchResults"></div>
                            </div>
                        </div>
                    </section>

                    <section id="categories" aria-labelledby="categories-heading">
                        <div class="section-header">
                            <h2 class="section-title" id="categories-heading">Explore Categories</h2>
                            <div class="section-line"></div>
                        </div>

                        <div class="category-grid">
                            @php
                                $categoryThemes = ['theme-college', 'theme-nursing', 'theme-allied', 'theme-insurance', 'theme-realestate', 'theme-it', 'theme-business', 'theme-teaching', 'theme-professional'];
                            @endphp
                            @foreach (($sidebarSchools ?? collect()) as $schoolIndex => $school)
                                @php $theme = $categoryThemes[$schoolIndex % count($categoryThemes)]; @endphp
                                <article class="cat-card {{ $theme }}" data-school-slug="{{ $school->slug }}">
                                    <div class="cat-card-header">
                                        <div class="cat-card-title-row">
                                            <div class="cat-icon-box" aria-hidden="true">
                                                @include('partials.sidebar-group-icon', ['groupKey' => $school->group_key])
                                            </div>
                                            <div class="cat-title-inner">
                                                <h3 class="cat-title">
                                                    <a
                                                        href="{{ route('subdivision.show', $school->slug) }}">{{ $school->schoolname }}</a>
                                                </h3>
                                                <span class="cat-badge">{{ $school->course_count }}
                                                    {{ Str::plural('course', $school->course_count) }}</span>
                                            </div>
                                            <svg class="cat-chevron" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                aria-hidden="true">
                                                <path d="m6 9 6 6 6-6"></path>
                                            </svg>
                                        </div>
                                        <div class="cat-divider"></div>
                                    </div>
                                    <div class="cat-list">
                                        @foreach ($school->courses as $sidebarCourse)
                                            <a class="cat-list-link"
                                                href="{{ course_url($school->slug, $sidebarCourse->slug) }}">
                                                <span>{{ $sidebarCourse->coursename }}</span>
                                                <svg class="cat-list-arrow" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    aria-hidden="true">
                                                    <path d="M5 12h14"></path>
                                                    <path d="m12 5 7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endforeach
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    @include('partials.whatsapp-widget')

</body>

</html>