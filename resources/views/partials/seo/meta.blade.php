@php
    use Illuminate\Support\Str;

    $seoTitle = Str::limit($seoTitle ?? config('app.name', 'Poker Exams'), 60, '…');
    $seoDescription = Str::limit($seoDescription ?? '', 160, '…');
    $seoCanonical = $seoCanonical ?? url()->current();
    $seoOgTitle = Str::limit($seoOgTitle ?? $seoTitle, 60, '…');
    $seoOgDescription = Str::limit($seoOgDescription ?? $seoDescription, 160, '…');
    $seoOgType = $seoOgType ?? 'website';
    $seoOgImage = $seoOgImage ?? asset('img/logo.png');
    $seoRobots = $seoRobots ?? 'index, follow';
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $seoCanonical }}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="googlebot" content="{{ $seoRobots }}">

<meta property="og:title" content="{{ $seoOgTitle }}">
<meta property="og:description" content="{{ $seoOgDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:type" content="{{ $seoOgType }}">
<meta property="og:image" content="{{ $seoOgImage }}">
<meta property="og:site_name" content="Poker Exams">
<meta property="og:locale" content="en_US">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoOgTitle }}">
<meta name="twitter:description" content="{{ $seoOgDescription }}">
<meta name="twitter:image" content="{{ $seoOgImage }}">
