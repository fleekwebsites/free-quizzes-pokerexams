@php
    $showCourseCount = $showCourseCount ?? false;
    $activeGroup = $activeGroup ?? null;
    $activeSubdivisionSlug = $activeSubdivisionSlug ?? null;
    $activeCourseSlug = $activeCourseSlug ?? null;
    $sidebarSchools = $sidebarSchools ?? collect();
    $sidebarCourseCount = $sidebarCourseCount ?? $sidebarSchools->sum('course_count');
@endphp
<div id="sidebarOverlay" class="sidebar-overlay"></div>

        <aside id="mainSidebar" class="sidebar-wrapper">
            <div class="sidebar-inner">
                <div class="sidebar-header">
            <a class="sidebar-all-exams" href="{{ route('library.index') }}">
                        <div class="all-exams-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 2v8l3-3 3 3V2"></path>
                                <path
                                    d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20">
                                </path>
                            </svg>
                        </div>
                        <div class="all-exams-text">
                            <p class="all-exams-title">All Exams</p>
                    @if ($showCourseCount && $sidebarCourseCount > 0)
                        <p class="all-exams-subtitle">{{ $sidebarCourseCount }} courses available</p>
                        @endif
                        </div>
                    </a>
                </div>

                <div class="sidebar-header">
            <a class="sidebar-all-exams sidebar-all-exams--indented" href="{{ main_url('/dashboard') }}">
                        <div class="all-exams-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg>
                        </div>

                        <div class="all-exams-text">
                            <p class="sidebar-link-label">My Dashboard</p>
                        </div>
                    </a>

            <a class="sidebar-all-exams sidebar-all-exams--indented" href="{{ main_url('/my-courses') }}">
                        <div class="all-exams-icon all-exams-icon--gold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                                </path>
                            </svg>
                        </div>
                        <div class="all-exams-text">
                            <p class="sidebar-link-label">My Courses</p>
                        </div>
                    </a>
                </div>

                <div class="sidebar-menu-list">
            @foreach ($sidebarSchools as $school)
                @php
                    $groupKey = $school->group_key;
                    $isActiveSchool = ($activeSubdivisionSlug ?? null) === $school->slug;
                    $iconClass = match ($groupKey) {
                        'business-finance', 'real-estate' => 'sb-indigo',
                        'college', 'teaching-certification' => 'sb-rose',
                        'high-school' => 'sb-emerald',
                        'insurance' => 'sb-violet',
                        'it-certification' => 'sb-amber',
                        'medical-allied-health' => 'sb-blue',
                        'nursing' => 'sb-orange',
                        'others' => 'sb-teal',
                        'professional-licensing' => 'sb-cyan',
                        default => 'sb-indigo',
                    };
                @endphp
                <div class="sidebar-group" data-group="{{ $groupKey }}">
                    <a href="{{ route('subdivision.show', $school->slug) }}"
                        class="sidebar-menu-btn{{ $isActiveSchool ? ' active' : '' }}"
                        @if ($isActiveSchool) aria-current="page" @endif>
                        <div class="sb-icon {{ $iconClass }}">
                            @include('partials.sidebar-group-icon', ['groupKey' => $groupKey])
                        </div>
                        <span class="sb-text">{{ $school->schoolname }}</span>
                        <span class="sb-count">{{ $school->course_count }}</span>
                    </a>
                    </div>
            @endforeach
                </div>
            </div>
        </aside>