@props(['title'])
@if (!empty($title))
    <meta property="og:title" content="{{ $title }}" />
    <meta name="twitter:title" content="{{ $title }}" />
@endif
