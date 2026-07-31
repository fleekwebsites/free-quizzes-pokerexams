@php
    $pageName = $pageName ?? '';
    $pageDescription = $pageDescription ?? '';
    $pageUrl = seo_absolute_url($pageUrl ?? url()->current());
    $listName = $listName ?? $pageName;
    $listItems = $listItems ?? [];

    $schemaElements = collect($listItems)
        ->values()
        ->map(function ($item, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => seo_absolute_url($item['url'] ?? ''),
            ];
        })
        ->filter(fn (array $item) => $item['item'] !== seo_absolute_url(''))
        ->values()
        ->all();

    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        '@id' => $pageUrl.'#webpage',
        'name' => $pageName,
        'description' => $pageDescription,
        'url' => $pageUrl,
        'isPartOf' => [
            '@type' => 'WebSite',
            '@id' => seo_absolute_url(route('library.index')).'#website',
            'name' => 'Poker Exams',
            'url' => seo_absolute_url(route('library.index')),
        ],
    ];

    if (count($schemaElements) > 0) {
        $collectionSchema['mainEntity'] = [
            '@type' => 'ItemList',
            'name' => $listName,
            'itemListElement' => $schemaElements,
        ];
    }
@endphp
<script type="application/ld+json">@json($collectionSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
