@props(['title', 'description', 'robots', 'keywords', 'og_title', 'og_description', 'og_image'])

@if (!empty($robots))
    <meta name="robots" content="{{ $robots }}">
@endif

@if (!empty($title))
    <title>{{ $title }}</title>
@endif

@if (!empty($description))
    <meta name="description" content="{{ $description }}">
@endif

@if (!empty($keywords))
    <meta name="keywords" content="{{ $keywords }}">
@endif

@if(!empty($og_title))
    <meta property="og:title" content="{{ $og_title }}" />
    <meta name="twitter:title" content="{{ $og_title }}" />
@endif

@if(!empty($og_description))
    <meta property="og:description" content="{{ $og_description }}" />
    <meta name="twitter:description" content="{{ $og_description }}" />
@endif

@if (!empty($og_image))
    <meta property="og:image" content="{{ asset('storage/' . $og_image) }}" />
    <meta name="twitter:image" content="{{ asset('storage/' . $og_image) }}" />
@endif
