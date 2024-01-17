@props(['title', 'description', 'robots', 'keywords', 'og_title', 'og_description', 'og_image'])

@include('laravel-meta::fields.robots')
@include('laravel-meta::fields.title')
@include('laravel-meta::fields.description')
@include('laravel-meta::fields.keywords')
@include('laravel-meta::fields.og_title')
@include('laravel-meta::fields.og_description')
@include('laravel-meta::fields.og_image')
