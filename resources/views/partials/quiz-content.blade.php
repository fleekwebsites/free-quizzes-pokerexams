@php
    $totalExamQuestions = $questions->count();
    $currentIndex = $currentIndex ?? 0;
    $imageBaseUrl = rtrim(config('pokerexams.main_site_url'), '/') . '/img/questions/';
@endphp

<div class="hidden lg:flex flex-col w-20 xl:w-24 shrink-0 lg:sticky lg:top-24 h-[calc(100vh-7rem)]">
    <div class="flex flex-col h-full">
        <div class="flex-1 overflow-y-auto px-1 py-3 pb-3 no-scrollbar" id="nav-scroll-container">
            <div class="grid grid-cols-2 gap-1.5 content-start" id="question-navigator">
                @for ($i = 0; $i < $totalExamQuestions; $i++)
                    <button onclick="goToSlide({{ $i }})" title="Question {{ $i + 1 }}"
                        class="nav-btn w-full aspect-square rounded-xl border-2 flex items-center justify-center text-xs font-bold transition-all duration-200 select-none cursor-pointer bg-white border-slate-300 text-slate-400 hover:border-[#06BBCC]/60 hover:text-[#06BBCC]"
                        id="nav-btn-{{ $i }}" data-state="unanswered" tabindex="0">
                        {{ $i + 1 }}
                    </button>
                @endfor
            </div>
        </div>
    </div>
</div>

<div class="lg:hidden fixed top-16 left-0 right-0 z-20 bg-white/95 backdrop-blur border-b border-slate-200 px-4 py-2">
    <div class="mb-1.5">
        <span class="text-xs font-bold text-slate-500" id="mobile-q-tracker">Q 1 / {{ $totalExamQuestions }}</span>
    </div>
    <div class="flex gap-0.5" id="mobile-progress-bar">
        @for ($i = 0; $i < $totalExamQuestions; $i++)
            <div class="flex-1 h-1.5 rounded-full transition-all mobile-dot bg-slate-200"></div>
        @endfor
    </div>
</div>

<div class="flex-1 min-w-0 px-2 lg:px-6 lg:pt-0 pt-16 pb-6" id="slides-wrapper">
    @forelse ($questions as $index => $q)
        @php
            $isMultipleChoice = isset($q->qtype) && str_contains(strtolower(trim($q->qtype)), 'multiple');
        @endphp

        <div class="question-slide" id="slide-{{ $index }}"
            style="display: {{ $index === $currentIndex ? 'block' : 'none' }};">
            <div class="mb-5">
                <span class="text-xs font-bold uppercase tracking-widest text-[#06BBCC]">Question
                    {{ $index + 1 }}</span>
                @if (!empty($q->extract))
                    <div class="mt-2 p-4 bg-slate-50 rounded-xl text-sm italic border-l-4 border-slate-300">
                        {!! nl2br(e($q->extract)) !!}
                    </div>
                @endif
                @if (!empty($q->heading))
                    <p class="mt-2 text-sm font-semibold text-slate-600">{{ $q->heading }}</p>
                @endif
                <p class="mt-2 text-xl lg:text-2xl font-bold text-[#1F2937] leading-relaxed">
                    {!! nl2br(e($q->question)) !!}
                </p>
                @if (!empty($q->image))
                    <img src="{{ $imageBaseUrl . $q->image }}" alt="" class="mt-4 rounded-xl max-w-full" loading="lazy">
                @endif
            </div>

            <div class="space-y-3 mb-6 options-container" data-question-id="{{ $q->id }}" data-index="{{ $index }}"
                data-multi="{{ $isMultipleChoice ? 'true' : 'false' }}"
                data-correct-answer="{{ e($q->correctAnswer) }}">
                @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $letter)
                    @if (!empty($q->{'choice' . $letter}))
                        @php
                            $choiceText = trim($q->{'choice' . $letter});
                            $isActualImage = preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $choiceText);
                        @endphp
                        <button
                            class="answer-option w-full text-left p-4 lg:p-5 rounded-2xl border-2 transition-all duration-200 border-slate-200 hover:border-[#06BBCC]/60 hover:bg-[#06BBCC]/3 cursor-pointer hover:shadow-sm"
                            data-value="{{ $letter }}" tabindex="0">
                            <div class="flex items-center gap-4">
                                <div
                                    class="option-icon w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-sm font-bold transition-all bg-slate-100 text-slate-500">
                                    {{ $letter }}
                                </div>
                                @if ($isActualImage)
                                    <img src="{{ $imageBaseUrl . $choiceText }}" alt="Option {{ $letter }}"
                                        class="max-w-full h-auto max-h-64 object-contain rounded-lg" loading="lazy">
                                @else
                                    <span class="text-sm lg:text-base text-[#1F2937] font-medium leading-snug">{{ $choiceText }}</span>
                                @endif
                            </div>
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="rationale-container overflow-hidden" style="display: none;">
                <div class="rounded-2xl border-2 p-5 mb-5 rationale-box border-slate-200 bg-slate-50/40">
                    <div class="flex flex-wrap gap-3 mb-3 r-header"></div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Rationale</p>
                    <div class="text-sm text-slate-700 leading-relaxed r-body">{!! nl2br(e($q->rationale ?? '')) !!}</div>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap mt-5">
                <button onclick="goToSlide({{ $index - 1 }})"
                    class="btn-prev-question flex items-center gap-2 px-5 py-3 bg-white border-2 border-slate-200 text-slate-600 font-semibold rounded-xl hover:border-slate-300 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                    {{ $index === 0 ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-arrow-left w-4 h-4">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg> Previous
                </button>

                @if ($isMultipleChoice)
                    <button
                        class="btn-check-answer ml-auto flex items-center gap-2 px-7 py-3 bg-[#06BBCC] text-white font-bold rounded-xl shadow-lg shadow-[#06BBCC]/20 hover:shadow-[#06BBCC]/40 hover:-translate-y-0.5 transition-all">
                        Check Answer
                    </button>
                    <button onclick="goToSlide({{ $index + 1 }})"
                        class="btn-next-question ml-auto flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-[#06BBCC] to-[#0597a7] text-white font-bold rounded-xl shadow-lg shadow-[#06BBCC]/20 hover:shadow-[#06BBCC]/40 hover:-translate-y-0.5 transition-all"
                        style="display: none;">
                        {{ $index === $totalExamQuestions - 1 ? 'Finish Exam' : 'Next Question' }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <div
                        class="select-to-continue ml-auto text-xs text-slate-400 font-medium px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                        Select an answer to continue →
                    </div>
                    <button onclick="goToSlide({{ $index + 1 }})"
                        class="btn-next-question ml-auto flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-[#06BBCC] to-[#0597a7] text-white font-bold rounded-xl shadow-lg shadow-[#06BBCC]/20 hover:shadow-[#06BBCC]/40 hover:-translate-y-0.5 transition-all"
                        style="display: none;">
                        {{ $index === $totalExamQuestions - 1 ? 'Finish Exam' : 'Next Question' }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="question-slide bg-white rounded-3xl p-8 lg:p-12 text-center shadow-sm border-2 border-slate-100"
            style="display: block;">
            <h2 class="text-2xl font-black text-slate-800 mb-3">Questions Coming Soon!</h2>
            <p class="text-slate-500 max-w-md mx-auto mb-8 leading-relaxed">No questions are available for this quiz
                yet.</p>
            <a href="{{ route('subdivision.show', $subdivision) }}"
                class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#06BBCC] to-[#0597a7] text-white font-bold rounded-xl">
                Back to {{ $subdivision->schoolname }}
            </a>
        </div>
    @endforelse
</div>