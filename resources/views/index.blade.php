<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Library - Study Resources | Poker Exams</title>
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="description"
        content="High-speed exam discovery platform with thousands of practice quizzes across major certifications.">
    <link rel="canonical" href="{{ $canonical ?? route('library.index') }}">

    <meta property="og:title" content="Practice Exam Library | Poker Exams">
    <meta property="og:description" content="Explore thousands of practice quizzes and certification study guides.">
    <meta property="og:url" content="{{ $canonical ?? route('library.index') }}">
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
          "name": "Library",
          "item": "{{ route('library.index') }}"
        }
      ]
    }
    </script>
</head>

<body>
    <div class="page-bg">
        @include('partials.header', ['showSearch' => false])

        @include('partials.sidebar', ['showCourseCount' => true])


        <main class="main-content-wrapper">
            <div class="main-content-inner">
                <div class="content-spacing">

                    <section class="hero-section">
                        <div class="hero-header">
                            <div>
                                <h1 class="hero-title">Practice Exams</h1>
                                <p class="hero-subtitle">{{ ($sidebarSchools ?? collect())->count() }} Categories · {{ $totalCourses ?? 0 }} Courses
                                </p>
                            </div>

                        </div>
                        <div class="hero-search" id="heroSearchWrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            <input type="text" id="heroSearchInput" class="hero-search-input"
                                placeholder="Search exams or quizzes…" autocomplete="off">

                            <div id="heroSearchDropdown" class="pe-search-dropdown">
                                <div id="heroSearchResults"></div>
                            </div>
                        </div>
                    </section>

                    <div id="categories">
                        <section>
                            <div class="section-header">
                                <h2 class="section-title">Explore Categories</h2>
                                <div class="section-line"></div>
                            </div>

                            <div class="category-grid">
                                @php
                                    $categoryThemes = ['theme-college', 'theme-nursing', 'theme-allied', 'theme-insurance', 'theme-realestate', 'theme-it', 'theme-business', 'theme-teaching', 'theme-professional'];
                                @endphp
                                @foreach (($sidebarSchools ?? collect()) as $schoolIndex => $school)
                                    @php $theme = $categoryThemes[$schoolIndex % count($categoryThemes)]; @endphp
                                    <div class="cat-card {{ $theme }}">
                                        <div class="cat-card-header">
                                            <div class="cat-card-title-row">
                                                <div class="cat-icon-box">
                                                    @include('partials.sidebar-group-icon', ['groupKey' => $school->group_key])
                                                </div>
                                                <div class="cat-title-inner">
                                                    <h3 class="cat-title">{{ $school->schoolname }}</h3>
                                                    <span class="cat-badge">{{ $school->course_count }} {{ Str::plural('course', $school->course_count) }}</span>
                                                </div>
                                                <svg class="cat-chevron" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M5 12h14"></path>
                                                        <path d="m12 5 7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>

                </div>
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