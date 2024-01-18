@props(['description'])
@if(!empty($description))
    <meta property="og:description" content="{{ $description }}" />
    <meta name="twitter:description" content="{{ $description }}" />
@endif
