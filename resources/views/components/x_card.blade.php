@props(['card'])
@if (!empty($card))
    <meta property="twitter:card" content="{{ $card }}" />
@endif
