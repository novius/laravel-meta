@if (!empty($og_image))
    <meta property="og:image" content="{{ asset('storage/' . $og_image) }}" />
    <meta name="twitter:image" content="{{ asset('storage/' . $og_image) }}" />
@endif
