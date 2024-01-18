@props(['robots'])
@if (!empty($robots))
    <meta name="robots" content="{{ $robots }}">
@endif
