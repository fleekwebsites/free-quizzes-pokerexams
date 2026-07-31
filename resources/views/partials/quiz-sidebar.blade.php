@php
    $otherExamLinks = $otherExams ?? collect();
    if (isset($similarExam) && $similarExam) {
        $otherExamLinks = $otherExamLinks->where('id', '!=', $similarExam->id)->values();
    }
@endphp
<aside class="w-full lg:w-64 shrink-0">
    <div class="lg:sticky lg:top-24 max-h-[calc(100vh-7rem)] overflow-y-auto no-scrollbar pb-6">
        <div class="bg-white rounded-2xl border-2 border-slate-200 overflow-hidden shadow-sm">
            <div class="border-b border-slate-100">
                <div class="px-4 py-2.5 bg-[#06BBCC]/10 border-b border-[#06BBCC]/15">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#06BBCC] leading-snug">
                        Similar Exams in {{ $currentCourseName ?? $courseName ?? $subdivision->schoolname }}
                    </p>
                </div>
                <div class="py-1 bg-[#06BBCC]/5">
                    @if ($similarExam)
                        <a href="{{ exam_url($subdivision->slug, $similarExam->slug, $currentCourse->slug ?? null) }}"
                            class="btn-switch-quiz w-full flex items-center gap-2.5 pl-4 pr-3 py-2.5 text-left border-l-2 border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition-all">
                            <span class="shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-circle w-3.5 h-3.5 text-slate-300">
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </span>
                            <span class="flex-1 truncate leading-snug text-xs font-medium">{{ $similarExam->title }}</span>
                        </a>
                    @else
                        <p class="px-4 py-3 text-xs text-slate-400">No similar exam in this category yet.</p>
                    @endif
                </div>
            </div>

            @if ($otherExamLinks->isNotEmpty())
                <div>
                    <div class="px-4 py-2.5 bg-slate-100 border-b border-slate-200">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-600">
                            Other Exams in {{ $subdivision->schoolname }}
                        </p>
                    </div>
                    <div class="divide-y divide-slate-100 bg-slate-50/80">
                        @foreach ($otherExamLinks as $otherExam)
                            <a href="{{ exam_url($subdivision->slug, $otherExam->slug) }}"
                                class="btn-switch-quiz w-full flex items-center gap-2.5 pl-4 pr-3 py-2.5 text-left border-l-2 transition-all {{ $otherExam->id === $exam->id ? 'border-[#06BBCC] bg-[#06BBCC]/8 text-[#06BBCC] font-semibold' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                                <span class="shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-circle w-3.5 h-3.5 {{ $otherExam->id === $exam->id ? 'text-[#06BBCC]' : 'text-slate-300' }}">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                </span>
                                <span class="flex-1 truncate leading-snug text-xs">{{ $otherExam->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</aside>