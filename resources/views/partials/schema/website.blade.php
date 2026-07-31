@php
    $siteUrl = seo_absolute_url($siteUrl ?? route('library.index'));
    $searchUrl = seo_absolute_url($searchUrl ?? route('search.query')).'?q={search_term_string}';

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => $siteUrl.'#website',
        'name' => 'Poker Exams',
        'url' => $siteUrl,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => $searchUrl,
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
@endphp
<script type="application/ld+json">@json($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
