@php
    $listName = $listName ?? '';
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

    $itemListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $listName,
        'itemListElement' => $schemaElements,
    ];
@endphp
@if (count($schemaElements) > 0)
    <script type="application/ld+json">@json($itemListSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@endif
