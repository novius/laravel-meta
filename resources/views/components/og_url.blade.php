@props(['url'])
@if (!empty($url))
    <meta property="og:url" content="{{ $url }}" />
@endif
