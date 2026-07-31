@php
    $categoryThemes = [
        'theme-college',
        'theme-nursing',
        'theme-allied',
        'theme-insurance',
        'theme-realestate',
        'theme-it',
        'theme-business',
        'theme-teaching',
        'theme-professional',
    ];
    $theme = $categoryThemes[$themeIndex ?? 0] ?? 'theme-college';
    $activeCourseSlug = $activeCourseSlug ?? null;
    $totalExams = $school->exam_count ?? $school->courses->sum(fn($course) => ($course->exams ?? collect())->count());
    $totalQuestions = $school->courses->sum(function ($course) {
        return ($course->exams ?? collect())->sum('question_count');
    });
@endphp

<section class="school-explorer {{ $theme }}">
    <div class="school-explorer-header">
        <div class="school-explorer-title-row">
            <div class="cat-icon-box">
                @include('partials.sidebar-group-icon', ['groupKey' => $school->group_key])
            </div>
            <div class="school-explorer-title-inner">
                <h1 class="school-explorer-title">{{ $school->schoolname }}</h1>
                <p class="school-explorer-meta">
                    {{ $school->course_count }} {{ Str::plural('course', $school->course_count) }}
                    · {{ $totalExams }} {{ Str::plural('quiz', $totalExams) }}
                    @if ($totalQuestions > 0)
                        · {{ $totalQuestions }} questions
                    @endif
                </p>
            </div>
        </div>
        <div class="cat-divider"></div>
    </div>

    <div class="school-course-grid">
        @foreach ($school->courses as $course)
            @php
                $courseExams = $course->exams ?? collect();
                $isActiveCourse = $activeCourseSlug === $course->slug;
            @endphp
            <article class="school-course-card{{ $isActiveCourse ? ' is-active' : '' }}" id="course-{{ $course->slug }}">
                <header class="school-course-header">
                    <h2 class="school-course-name">{{ $course->coursename }}</h2>
                    <span class="school-course-badge">
                        {{ $courseExams->count() }} {{ Str::plural('quiz', $courseExams->count()) }}
                    </span>
                </header>

                <div class="school-exam-list">
                    @forelse ($courseExams as $exam)
                        <a class="school-exam-link" href="{{ exam_url($school->slug, $exam->slug, $course->slug) }}">
                            <span class="school-exam-title">{{ $exam->title }}</span>
                            <span class="school-exam-meta">{{ $exam->question_count }} Qs</span>
                        </a>
                    @empty
                        <p class="school-exam-empty">No quizzes available yet.</p>
                    @endforelse
                </div>
            </article>
        @endforeach
    </div>
</section>