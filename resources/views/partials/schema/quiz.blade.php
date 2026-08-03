@php
    $questionParts = $questions->map(function ($question, $index) {
        $choices = [];
        $position = 1;

        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $letter) {
            $text = trim((string) ($question->{'choice'.$letter} ?? ''));
            if ($text === '') {
                continue;
            }

            $choices[] = [
                '@type' => 'Answer',
                'position' => $position,
                'text' => strip_tags($text),
            ];
            $position++;
        }

        $correctLetters = array_values(array_filter(array_map('trim', explode(',', (string) $question->correctAnswer))));
        $acceptedAnswers = [];

        foreach ($correctLetters as $letter) {
            $answerText = trim((string) ($question->{'choice'.$letter} ?? ''));
            if ($answerText !== '') {
                $acceptedAnswers[] = [
                    '@type' => 'Answer',
                    'text' => strip_tags($answerText),
                ];
            }
        }

        $isMultiple = isset($question->qtype) && str_contains(strtolower(trim((string) $question->qtype)), 'multiple');

        return [
            '@type' => 'Question',
            'position' => $index + 1,
            'name' => \Illuminate\Support\Str::limit(strip_tags((string) $question->question), 500, '…'),
            'eduQuestionType' => $isMultiple ? 'Multiple choice' : 'Single choice',
            'acceptedAnswer' => count($acceptedAnswers) === 1 ? $acceptedAnswers[0] : $acceptedAnswers,
            'suggestedAnswer' => $choices,
        ];
    })->values()->all();

    $organization = [
        '@type' => 'Organization',
        'name' => 'Poker Exams',
        'url' => config('pokerexams.main_site_url', 'https://pokerexams.com'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => seo_absolute_url(asset('img/logo.png')),
        ],
    ];

    $quizSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Quiz',
        '@id' => seo_absolute_url($canonical).'#quiz',
        'name' => $exam->title,
        'description' => 'Free practice quiz: '.$exam->title.' for '.$subdivision->schoolname,
        'url' => seo_absolute_url($canonical),
        'author' => $organization,
        'publisher' => $organization,
        'educationalLevel' => 'Professional',
        'learningResourceType' => 'Quiz',
        'interactivityType' => 'active',
        'numberOfQuestions' => $questions->count(),
        'hasPart' => $questionParts,
    ];

    if ($publishedAt = ($exam->created_at ?? $exam->updated_at)) {
        $quizSchema['datePublished'] = $publishedAt->toIso8601String();
    }
@endphp
<script type="application/ld+json">@json($quizSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
