@props(['locale'])
@if (!empty($locale))
    <meta property="og:locale" content="{{ $locale }}" />
@endif
