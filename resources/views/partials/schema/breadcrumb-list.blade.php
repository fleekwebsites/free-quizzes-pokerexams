@php
    $breadcrumbItems = $breadcrumbItems ?? [];
    $pageUrl = seo_absolute_url($pageUrl ?? url()->current());
    $pageName = $pageName ?? null;
    $pageDescription = $pageDescription ?? '';

    $items = collect($breadcrumbItems)->values();
    if ($items->count() < 2) {
        return;
    }

    $lastIndex = $items->count() - 1;
    $schemaItems = $items
        ->map(function ($item, $index) use ($lastIndex) {
            $listItem = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
            ];

            if ($index !== $lastIndex) {
                $listItem['item'] = seo_absolute_url($item['url'] ?? $pageUrl);
            }

            return $listItem;
        })
        ->all();

    $breadcrumbId = $pageUrl.'#breadcrumb';
    $breadcrumbSchema = [
        '@type' => 'BreadcrumbList',
        '@id' => $breadcrumbId,
        'itemListElement' => $schemaItems,
    ];

    if ($pageName) {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebPage',
                    '@id' => $pageUrl.'#webpage',
                    'url' => $pageUrl,
                    'name' => $pageName,
                    'description' => $pageDescription,
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        '@id' => seo_absolute_url(route('library.index')).'#website',
                        'name' => 'Poker Exams',
                        'url' => seo_absolute_url(route('library.index')),
                    ],
                    'breadcrumb' => ['@id' => $breadcrumbId],
                ],
                $breadcrumbSchema,
            ],
        ];
    } else {
        $structuredData = array_merge(
            ['@context' => 'https://schema.org'],
            $breadcrumbSchema
        );
    }
@endphp
<script type="application/ld+json">@json($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
