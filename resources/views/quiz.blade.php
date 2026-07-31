<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.seo.meta', [
        'seoTitle' => ($exam->title ?? 'Free Quiz') . ' | Poker Exams',
        'seoDescription' => 'Free ' . ($exam->title ?? 'practice') . ' quiz with instant rationales for ' . $subdivision->schoolname . '.',
        'seoCanonical' => $canonical,
        'seoOgTitle' => ($exam->title ?? 'Free Quiz') . ' | Poker Exams',
        'seoOgDescription' => 'Free practice quiz with rationales for ' . $subdivision->schoolname . '.',
        'seoOgType' => 'article',
    ])

    @include('partials.seo.assets')
    <script src="{{ asset('js/library.js') }}" defer></script>
    <script>
        window.quizConfig = {
            totalQuestions: {{ $totalExamQuestions ?? ($exam->question_count ?? 0) }},
            examId: {{ isset($exam) ? $exam->id : 1 }},
            submitUrl: "{{ route('exam.submit') }}",
            csrfToken: "{{ csrf_token() }}",
            subdivisionUrl: "{{ route('subdivision.show', $subdivision) }}",
            mainLibraryUrl: "{{ main_url('/library') }}",
            freeQuizzesUrl: "{{ route('library.index') }}",
            examTitle: @json($exam->title ?? 'this quiz'),
        };
    </script>

    @include('partials.schema.quiz', [
        'exam' => $exam,
        'questions' => $questions,
        'subdivision' => $subdivision,
        'canonical' => $canonical,
    ])

    @include('partials.schema.breadcrumb-list', [
        'pageUrl' => $canonical,
        'pageName' => $exam->title,
        'pageDescription' => 'Free practice quiz with rationales for ' . $subdivision->schoolname . '.',
        'breadcrumbItems' => [
            ['name' => 'Home', 'url' => route('library.index')],
            ['name' => $subdivision->schoolname, 'url' => route('subdivision.show', $subdivision)],
            ['name' => $currentCourse->coursename, 'url' => course_url($subdivision->slug, $currentCourse->slug)],
            ['name' => $exam->title, 'url' => $canonical],
        ],
    ])
</head>

<body>
    <div id="root">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
            @include('partials.header', ['showMobileMenu' => false])

            <div class="pt-16">
                <div class="max-w-7xl mx-auto px-2 sm:px-0">

                    <div class="flex items-center justify-between gap-1 sm:gap-2 mb-4 mt-4 exam-action-bar flex-nowrap">
                        <nav class="flex items-center gap-1 text-sm flex-nowrap min-w-0 flex-1 overflow-hidden" aria-label="Breadcrumb">
                            <a class="flex items-center gap-1 text-slate-400 hover:text-[#06BBCC] transition-colors shrink-0"
                                href="{{ route('library.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-house w-3.5 h-3.5">
                                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                                    <path
                                        d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Home</span>
                            </a>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-right w-3.5 h-3.5 text-slate-300 shrink-0">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <a class="exam-crumb-school text-slate-400 hover:text-[#06BBCC] transition-colors truncate"
                                href="{{ route('subdivision.show', $subdivision) }}">{{ $subdivision->schoolname }}</a>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-right w-3.5 h-3.5 text-slate-300 shrink-0">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <a class="exam-crumb-course text-slate-400 hover:text-[#06BBCC] transition-colors truncate"
                                href="{{ course_url($subdivision->slug, $currentCourse->slug) }}">{{ $currentCourse->coursename }}</a>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-right w-3.5 h-3.5 text-slate-300 shrink-0">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <h1 class="exam-crumb-title text-[#1F2937] font-semibold truncate min-w-0">
                                {{ $exam->title }}</h1>
                        </nav>

                        <div class="flex items-center gap-0.5 action-buttons-group shrink-0">
                            <div class="relative">
                                <button id="btn-rate-quiz"
                                    class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all text-slate-400 hover:text-amber-500 hover:bg-amber-50"
                                    title="Rate this quiz">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-star w-3.5 h-3.5">
                                        <path
                                            d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">Rate</span>
                                </button>

                            </div>

                            <button
                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all text-slate-400 hover:text-[#06BBCC] hover:bg-[#06BBCC]/5"
                                title="Copy link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-share2 w-3.5 h-3.5">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                                    <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
                                </svg>
                                <span class="hidden sm:inline">Share</span>
                            </button>

                            <button
                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-red-400 hover:bg-red-50 transition-all"
                                title="Flag an issue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-flag w-3.5 h-3.5">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                    <line x1="4" x2="4" y1="22" y2="15"></line>
                                </svg>
                                <span class="hidden sm:inline">Flag</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-5 items-start">

                        <div class="flex-1 min-w-0 w-full">
                            <div class="flex gap-0 min-h-[calc(100vh-120px)]">

                                @include('partials.quiz-content')

                            </div>
                        </div>

                        @include('partials.quiz-sidebar')
                    </div>
                </div>
            </div>

            <div id="modal-exit-quiz"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4"
                data-nosnippet="">
                <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl" data-nosnippet="">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-triangle-alert w-6 h-6 text-amber-500">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                            <path d="M12 9v4"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-[#1F2937] text-center mb-1">You are leaving this quiz</h3>
                    <p class="text-sm text-slate-500 text-center mb-6">What would you like to do with your progress?</p>
                    <div class="flex flex-col gap-2.5">
                        <a href="{{ route('library.index') }}"
                            class="w-full py-3.5 bg-gradient-to-r from-[#06BBCC] to-[#0597a7] flex justify-center items-center text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-[#06BBCC]/30 transition-all">Save
                            Progress &amp; Exit</a>
                        <a href="{{ main_url('/library/courses/exams/questions/praxis-5001-test-with-answers/retake') }}"
                            class="w-full py-3.5 bg-red-50 text-red-600 flex justify-center items-center font-bold rounded-2xl hover:bg-red-100 transition-all">Retake
                            / Clear Answers</a>
                        <button id="btn-cancel-exit"
                            class="w-full py-3.5 bg-slate-100 text-slate-600 font-semibold rounded-2xl hover:bg-slate-200 transition-all">Cancel
                            — Stay in Quiz</button>
                    </div>
                </div>
            </div>

            <div id="modal-switch-quiz"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4"
                data-nosnippet="">
                <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl" data-nosnippet="">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-2xl bg-amber-50 border-2 border-amber-200 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-triangle-alert w-5 h-5 text-amber-500">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3">
                                </path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">You're in the middle of a quiz</h3>
                            <p class="text-slate-500 text-xs mt-0.5">What would you like to do before switching?</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button id="btn-save-switch"
                            class="w-full flex items-center gap-3 px-4 py-3.5 bg-[#06BBCC]/10 border-2 border-[#06BBCC]/30 text-[#06BBCC] font-semibold rounded-xl hover:bg-[#06BBCC]/20 transition-all text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-save w-4 h-4 shrink-0">
                                <path
                                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z">
                                </path>
                                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path>
                                <path d="M7 3v4a1 1 0 0 0 1 1h7"></path>
                            </svg>Save progress &amp; continue later
                        </button>
                        <button id="btn-abandon-switch"
                            class="w-full flex items-center gap-3 px-4 py-3.5 bg-red-50 border-2 border-red-100 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-trash2 w-4 h-4 shrink-0">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                <line x1="14" x2="14" y1="11" y2="17"></line>
                            </svg>Abandon quiz &amp; switch
                        </button>
                        <button id="btn-cancel-switch"
                            class="w-full flex items-center gap-3 px-4 py-3.5 bg-slate-50 border-2 border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-100 transition-all text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-x w-4 h-4 shrink-0">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>Stay on this quiz
                        </button>
                    </div>
                </div>
            </div>

            <div id="modal-exam-complete"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4"
                data-nosnippet="">
                <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl" data-nosnippet="">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-2xl bg-emerald-50 border-2 border-emerald-200 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-circle-check w-5 h-5 text-emerald-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Practice complete</h3>
                            <p class="text-slate-500 text-xs mt-0.5" id="exam-complete-summary">
                                You finished this free practice quiz.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <a href="{{ main_url('/library') }}" id="exam-complete-main-library"
                            class="w-full flex items-center justify-center px-4 py-3.5 bg-[#06BBCC]/10 border-2 border-[#06BBCC]/30 text-[#06BBCC] font-semibold rounded-xl hover:bg-[#06BBCC]/20 transition-all text-sm">
                            Go to full exam library
                        </a>
                        <a href="{{ route('library.index') }}" id="exam-complete-free-quizzes"
                            class="w-full flex items-center justify-center px-4 py-3.5 bg-slate-50 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-100 transition-all text-sm">
                            Continue with more free quizzes
                        </a>
                        <button type="button" id="btn-review-complete"
                            class="w-full flex items-center justify-center px-4 py-3.5 bg-white border-2 border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all text-sm">
                            Review my answers
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="intToast" class="int-toast"></div>

    <div class="int-modal-overlay" id="intModalFlag" data-nosnippet="">
        <div class="int-modal-box" data-nosnippet="">
            <button class="int-modal-close" aria-label="Close modal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <h3 class="int-modal-title">Report an Issue</h3>
            <p class="int-modal-subtitle">Help us improve by flagging this content.</p>

            <form id="intFormFlag" action="{{ route('interaction.flag') }}">
                <div class="int-form-group">
                    <label class="int-label">Reason</label>
                    <select name="reason" class="int-select" required="">
                        <option value="" disabled="" selected="">Select a reason...</option>
                        <option value="Incorrect Answer">Incorrect Answer / Rationale</option>
                        <option value="Typo">Typo / Grammatical Error</option>
                        <option value="Broken Image">Broken Image or Layout</option>
                        <option value="Outdated">Outdated Information</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="int-form-group">
                    <label class="int-label">Additional Comments (Optional)</label>
                    <textarea name="comment" class="int-textarea"
                        placeholder="Please provide more details so we can fix it quickly."></textarea>
                </div>
                <button type="submit" class="int-btn-submit">Submit Report</button>
            </form>
        </div>
    </div>

    <div class="int-modal-overlay" id="intModalRate" data-nosnippet="">
        <div class="int-modal-box" data-nosnippet="">
            <button class="int-modal-close" aria-label="Close modal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <h3 class="int-modal-title" style="text-align: center;">Rate this Practice Test</h3>
            <p class="int-modal-subtitle" style="text-align: center;">How helpful was this material?</p>

            <form id="intFormRate" action="{{ route('interaction.rate') }}">
                <div class="int-stars">
                    <input type="radio" name="rating" id="star5" class="int-star-input" value="5" required="">
                    <label for="star5" class="int-star-label"><svg viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                            </path>
                        </svg></label>

                    <input type="radio" name="rating" id="star4" class="int-star-input" value="4">
                    <label for="star4" class="int-star-label"><svg viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                            </path>
                        </svg></label>

                    <input type="radio" name="rating" id="star3" class="int-star-input" value="3">
                    <label for="star3" class="int-star-label"><svg viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                            </path>
                        </svg></label>

                    <input type="radio" name="rating" id="star2" class="int-star-input" value="2">
                    <label for="star2" class="int-star-label"><svg viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                            </path>
                        </svg></label>

                    <input type="radio" name="rating" id="star1" class="int-star-input" value="1">
                    <label for="star1" class="int-star-label"><svg viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                            </path>
                        </svg></label>
                </div>

                <div class="int-form-group">
                    <label class="int-label">Review (Optional)</label>
                    <textarea name="comment" class="int-textarea"
                        placeholder="What did you like? What could be improved?"></textarea>
                </div>
                <button type="submit" class="int-btn-submit">Submit Rating</button>
            </form>
        </div>
    </div>

    @include('partials.whatsapp-widget')
    <div class="exam-sidebar-overlay"></div>
</body>

</html>