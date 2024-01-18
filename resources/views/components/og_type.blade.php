@props(['type'])
@if (!empty($type))
    <meta property="og:type" content="{{ $type }}" />
@endif
