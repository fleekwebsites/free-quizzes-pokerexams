<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $exam->title ?? 'Free Quiz' }} - Poker Exams</title>
    <meta name="description"
        content="Take our free {{ $exam->title ?? 'practice' }} quiz. Access detailed rationales and exam questions for {{ $courseName ?? $subdivision->schoolname ?? 'this certification' }}."
        id="meta-description">

    <link rel="canonical" href="{{ $canonical }}">

    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">

    <meta property="og:title" content="{{ $exam->title ?? 'Free Quiz' }} - Poker Exams">
    <meta property="og:description"
        content="Take our free {{ $exam->title ?? 'practice' }} quiz with detailed rationales and exam questions.">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="{{ asset('css/inter-font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/library.css') }}" rel="stylesheet">
    <script src="{{ asset('js/library.js') }}" defer></script>
    <script>
        window.quizConfig = {
            totalQuestions: {{ $totalExamQuestions ?? ($exam->question_count ?? 0) }},
            examId: {{ isset($exam) ? $exam->id : 1 }},
            submitUrl: "{{ route('exam.submit') }}",
            csrfToken: "{{ csrf_token() }}",
            subdivisionUrl: "{{ isset($currentCourse) && $currentCourse ? course_url($subdivision->slug, $currentCourse->slug) : route('subdivision.show', $subdivision) }}"
        };
    </script>

    <script type="application/ld+json">{
    "@@context": "https://schema.org",
    "@@type": "Quiz",
    "name": "Praxis 5001 Test with Answers",
    "description": "Practice exam questions for Praxis 5001 Test with Answers",
    "educationalLevel": "Professional",
    "learningResourceType": "Quiz",
    "interactivityType": "mixed",
    "hasPart": [
        {
            "@@type": "Question",
            "position": 1,
            "text": "Which THREE of the following statements best describe how improved fluency impacts a student's comprehension?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "",
                "explanation": {
                    "@@type": "Comment",
                    "text": "Students experience greater comprehension and ability to make connections to the text.  \nImproved fluency allows students to read smoothly and with expression, which enhances their understanding and ability to relate ideas within the text. This fluency fosters deeper connections, leading to better overall comprehension.\n\nA) Students experience greater comprehension and ability to make connections to the text.  \nThis statement accurately reflects the relationship between fluency and comprehension. As students read more fluently, they can connect ideas and themes within the text more effectively, enhancing their overall understanding.\n\nB) Students are able to monitor intonation and punctuation to understand meaning of the text.  \nThis assertion is also true, as fluency helps students recognize and apply intonation and punctuation cues, which are crucial for grasping the meaning of the text. This skill supports comprehension by allowing readers to interpret sentences as intended by the author.\n\nC) Students are able to focus on the meaning of the text rather than on laboring through reading the words.  \nThis statement highlights a fundamental benefit of fluency. When students read with fluency, they can devote their cognitive resources to understanding the content, rather than struggling to decode words, which improves their overall comprehension.\n\nD) Students experience a neutral effect on their comprehension but develop a faster reading rate.  \nThis choice is incorrect because it suggests that fluency has no positive impact on comprehension. In reality, improved fluency is linked to enhanced understanding, as students can engage with the text more meaningfully.\n\nE) Students are able to recall facts at a higher rate as they gain automaticity.  \nWhile this statement may seem plausible, it misrepresents the direct link between fluency and comprehension. Recall of facts may improve due to automaticity, but the focus here is on comprehension, which is better supported by fluency in reading.\n\nConclusion  \nImproved fluency positively influences a student's comprehension by enabling them to connect ideas, understand meaning through intonation, and focus on content rather than decoding. Choices A, B, and C accurately describe these benefits, while choices D and E mischaracterize the impact of fluency on comprehension. Understanding this relationship is vital for effective reading instruction and fostering students' engagement with texts."
                }
            },
            "suggestedAnswer": [
                {
                    "@@type": "Answer",
                    "text": "Students experience greater comprehension and ability to make connections to the text.",
                    "position": 1
                },
                {
                    "@@type": "Answer",
                    "text": "Students are able to monitor intonation and punctuation to understand meaning of the text.",
                    "position": 2
                },
                {
                    "@@type": "Answer",
                    "text": "Students are able to focus on the meaning of the text rather than on laboring through reading the words.",
                    "position": 3
                },
                {
                    "@@type": "Answer",
                    "text": "Students experience a neutral effect on their comprehension but develop a faster reading rate.",
                    "position": 4
                },
                {
                    "@@type": "Answer",
                    "text": "Students are able to recall facts at a higher rate as they gain automaticity.",
                    "position": 5
                }
            ]
        },
        {
            "@@type": "Question",
            "position": 2,
            "text": "In which of the following words is the time underlined?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Dance",
                "explanation": {
                    "@@type": "Comment",
                    "text": "In the word \"Dance,\" the time is underlined.  \nThe word \"Dance\" includes the syllable \"dance,\" which can refer to a specific time or event, such as a formal gathering or a scheduled performance. This makes it the only word in the provided options where the concept of time is explicitly conveyed.\n\nA) Cat  \nThe word \"Cat\" does not imply any notion of time. It refers solely to a type of animal and lacks any temporal context or meaning, making it irrelevant to the question regarding time.\n\nB) Mock  \n\"Mock\" primarily means to imitate or make fun of someone or something. While it signifies an action, it does not convey a specific time or event associated with that action, thus failing to meet the criteria set by the question.\n\nC) Throw  \nThe word \"Throw\" denotes an action, typically involving launching an object. Like \"Mock,\" it does not reference a specific time or event, and therefore cannot be considered relevant to the question about indicating time.\n\nD) Dance  \n\"Dance\" can refer to an event where people gather to engage in a rhythmic movement, often at a specific time, such as a \"dance party\" or \"dance recital.\" This association with a scheduled event makes it the only choice that includes a concept of time.\n\nConclusion  \nAmong the words provided, only \"Dance\" conveys a direct association with time through its reference to events or gatherings that occur at specific times. The other options do not carry any temporal implications, making \"Dance\" the clear answer to the question."
                }
            },
            "suggestedAnswer": [
                {
                    "@@type": "Answer",
                    "text": "Cat",
                    "position": 1
                },
                {
                    "@@type": "Answer",
                    "text": "Mock",
                    "position": 2
                },
                {
                    "@@type": "Answer",
                    "text": "Throw",
                    "position": 3
                },
                {
                    "@@type": "Answer",
                    "text": "Dance",
                    "position": 4
                }
            ]
        },
        {
            "@@type": "Question",
            "position": 3,
            "text": "Research indicates that the most effective way to help a student with limited English proficiency to maximize the acquisition of English is to provide",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "a classroom environment that openly accepts the student's language and expression",
                "explanation": {
                    "@@type": "Comment",
                    "text": "a classroom environment that openly accepts the student's language and expression.  \nCreating an inclusive classroom atmosphere that values a student's native language fosters confidence and encourages communication, which are critical for language acquisition. When students feel accepted and understood, they are more likely to engage and practice their English skills without fear of judgment.\n\nA) audiotapes that drill in Standard English pronunciation  \nWhile audiotapes can be helpful for improving pronunciation, they do not address the broader emotional and social aspects of language learning. Relying solely on such drills may create a mechanical learning environment that neglects the importance of interaction and contextual use of language, which are vital for effective communication.\n\nB) tutoring in the grammar of Standard English  \nTutoring focused on grammar may enhance a student's understanding of language rules, but it may not effectively promote real-world communication skills. This approach can be overly focused on formal structures, potentially discouraging students from using English in practical, conversational contexts where they might feel more comfortable expressing themselves.\n\nC) placement in a reading group that includes others who use the student's first language  \nWhile this placement may offer some comfort and support, it can inadvertently isolate the student from full immersion in English. Such an arrangement could limit their opportunities to practice English with native speakers and may not provide the necessary challenges that facilitate language growth and integration into the broader classroom environment.\n\nConclusion  \nTo effectively support students with limited English proficiency, a welcoming classroom environment that embraces their native language while encouraging English expression is essential. This approach not only builds confidence but also promotes active engagement and communication, which are crucial for language acquisition. By prioritizing acceptance and open expression, educators can create a supportive learning space that enhances language development and fosters academic success."
                }
            },
            "suggestedAnswer": [
                {
                    "@@type": "Answer",
                    "text": "audiotapes that drill in Standard English pronunciation",
                    "position": 1
                },
                {
                    "@@type": "Answer",
                    "text": "tutoring in the grammar of Standard English",
                    "position": 2
                },
                {
                    "@@type": "Answer",
                    "text": "placement in a reading group that includes others who use the student's first language",
                    "position": 3
                },
                {
                    "@@type": "Answer",
                    "text": "a classroom environment that openly accepts the student's language and expression",
                    "position": 4
                }
            ]
        },
        {
            "@@type": "Question",
            "position": 4,
            "text": "Which of the following correctly identifies the number of phonemes in the word \"twice\"?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "4",
                "explanation": {
                    "@@type": "Comment",
                    "text": "The word \"twice\" contains 4 phonemes.  \nPhonemes are the distinct units of sound in a word that contribute to its pronunciation. In the case of \"twice,\" it can be broken down into four phonemes: /t/, /w/, /aɪ/, and /s/. This segmentation demonstrates how the sounds combine to form the spoken word.\n\nA) 1  \nIdentifying only one phoneme for \"twice\" overlooks the complexity of its sound structure. A single phoneme would suggest that the word is pronounced as one uninterrupted sound, which is inaccurate since \"twice\" clearly consists of multiple distinct sounds.\n\nB) 2  \nClaiming that \"twice\" contains two phonemes ignores the additional sounds present in the word. While it has two syllables, the number of phonemes is derived from the distinct sounds involved, which includes the /aɪ/ diphthong that contributes to its phonetic breakdown.\n\nC) 4  \nThe correct analysis reveals that \"twice\" has four phonemes: /t/, /w/, /aɪ/, and /s/. Each of these sounds plays a crucial role in the pronunciation of the word, confirming that it indeed consists of four distinct phonetic elements.\n\nD) 5  \nAssuming that \"twice\" contains five phonemes would imply the presence of an additional sound that does not exist in its pronunciation. The breakdown of the word only accounts for four phonemes, making five an inaccurate representation of its sound structure.\n\nConclusion  \nThe word \"twice\" comprises four phonemes, which are essential for its correct articulation. Each phoneme contributes to the overall pronunciation, confirming that the total count is four. Understanding phonemes is vital for linguistic studies, particularly in phonetics and phonology, where sound patterns and their roles in language are analyzed."
                }
            },
            "suggestedAnswer": [
                {
                    "@@type": "Answer",
                    "text": "1",
                    "position": 1
                },
                {
                    "@@type": "Answer",
                    "text": "2",
                    "position": 2
                },
                {
                    "@@type": "Answer",
                    "text": "4",
                    "position": 3
                },
                {
                    "@@type": "Answer",
                    "text": "5",
                    "position": 4
                }
            ]
        },
        {
            "@@type": "Question",
            "position": 5,
            "text": "A teacher draws a picture of a clock. The teacher asks a student to draw a picture of the word that is created when you take away the first \"c\" sound. The student draws a lock. Which of the following best describes the phonological skill the student is practicing?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Deleting",
                "explanation": {
                    "@@type": "Comment",
                    "text": "Deleting.  \nThe student demonstrates the phonological skill of deleting by removing the initial \"c\" sound from the word \"clock,\" resulting in the word \"lock.\" This process involves manipulating sounds within a word, which is a key aspect of phonological awareness.\n\nA) Blending  \nBlending involves combining individual sounds or phonemes to form a word. In this scenario, the student is not combining sounds but rather removing one, making blending an incorrect description of the activity.\n\nB) Segmenting  \nSegmenting refers to the ability to break a word down into its individual sounds or phonemes. While the student is aware of the sounds in \"clock,\" they are not segmenting it into parts; instead, they are deleting one sound entirely. Therefore, segmenting does not accurately describe the skill being practiced.\n\nC) Substituting  \nSubstituting involves replacing one sound in a word with another to create a different word. The student is not replacing any sounds in \"clock\" but is removing an initial sound, which does not align with the definition of substituting.\n\nD) Deleting  \nThis choice correctly identifies the action taken by the student. By taking away the \"c\" sound from \"clock,\" the student is practicing the skill of deleting, which is a fundamental part of phonological processing.\n\nConclusion  \nIn this exercise, the student practices the phonological skill of deleting by omitting the initial \"c\" sound from \"clock\" to form \"lock.\" This specific skill is crucial for developing reading and spelling abilities, as it enhances the understanding of sound manipulation within words. The other options—blending, segmenting, and substituting—do not accurately reflect the task at hand, reinforcing the significance of identifying specific phonological skills in language development."
                }
            },
            "suggestedAnswer": [
                {
                    "@@type": "Answer",
                    "text": "Blending",
                    "position": 1
                },
                {
                    "@@type": "Answer",
                    "text": "Segmenting",
                    "position": 2
                },
                {
                    "@@type": "Answer",
                    "text": "Substituting",
                    "position": 3
                },
                {
                    "@@type": "Answer",
                    "text": "Deleting",
                    "position": 4
                }
            ]
        }
    ]
}</script>

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
          "name": "{{ $courseName ?? $subdivision->schoolname }}",
          "item": "{{ isset($currentCourse) && $currentCourse ? course_url($subdivision->slug, $currentCourse->slug) : route('subdivision.show', $subdivision) }}"
        },
        {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{ $exam->title }}",
          "item": "{{ $canonical }}"
        }
      ]
    }
    </script>
</head>
<body>
    <div id="root">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
            @include('partials.header', ['showMobileMenu' => false])

            <div class="pt-16">
                <div class="max-w-7xl mx-auto px-2 sm:px-0">

                    <div class="flex items-center justify-between gap-2 mb-4 mt-4 exam-action-bar">
                        <nav class="flex items-center gap-1.5 text-sm flex-wrap min-w-0 w-full">
                            <a class="flex items-center gap-1 text-slate-400 hover:text-[#06BBCC] transition-colors shrink-0"
                                href="/">
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
                            <a class="text-slate-400 hover:text-[#06BBCC] transition-colors truncate max-w-[100px] sm:max-w-[200px]"
                                href="{{ isset($currentCourse) && $currentCourse ? course_url($subdivision->slug, $currentCourse->slug) : route('subdivision.show', $subdivision) }}">{{ $courseName ?? $subdivision->schoolname }}</a>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-chevron-right w-3.5 h-3.5 text-slate-300 shrink-0">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <h1 class="text-[#1F2937] font-semibold truncate max-w-[120px] sm:max-w-xs">{{ $exam->title }}</h1>
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
                <div class="bg-white rounded-3xl p-7 max-w-sm w-full shadow-2xl" style="transform: scale(1.00282);">
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
                <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl" style="transform: scale(1.00227);">
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

        </div>
    </div>
    <div id="intToast" class="int-toast"></div>

    <div class="int-modal-overlay" id="intModalFlag" data-nosnippet="">
        <div class="int-modal-box">
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
        <div class="int-modal-box">
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

    <a href="https://api.whatsapp.com/send/?phone=15107718152&amp;text=Hello&amp;type=phone_number&amp;app_absent=0"
        class="wa-floating-widget" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24">
            <path fill="currentColor"
                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157.1zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z">
            </path>
        </svg>
        <span class="wa-text">Chat on WhatsApp</span>
    </a>
<div class="exam-sidebar-overlay"></div>
</body>

</html>