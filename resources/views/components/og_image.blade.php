@props(['image'])
@if(!empty($image))
    <meta property="og:image" content="{{ $image }}" />
    <meta name="twitter:image" content="{{ $image }}" />
@endif

