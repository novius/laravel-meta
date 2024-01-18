@props(['name'])
@if (!empty($name))
    <meta property="og:site_name" content="{{ $name }}" />
@endif
