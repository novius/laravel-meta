@php
/** @var \Illuminate\Database\Eloquent\Model&\Novius\LaravelMeta\Traits\HasMeta|null $model */
$model = \Novius\LaravelMeta\Facades\CurrentModel::getModel();
if ($model === null || !in_array(\Novius\LaravelMeta\Traits\HasMeta::class, class_uses_recursive($model), true)) {
    $model = null;
}
@endphp

<x-meta-robots :robots="$model?->seo_robots" />
<x-meta-title :title="$model?->seo_title.' | '.config('app.name')" />
<x-meta-description :description="$model?->seo_description" />
<x-meta-keywords :keywords="$model?->seo_keywords" />
<x-meta-og-type :type="$model?->og_type" />
<x-meta-og-title :title="$model?->og_title" />
<x-meta-og-description :description="$model?->og_description" />
<x-meta-og-image :image="$model?->og_image_url" />
<x-meta-og-url :url="\Illuminate\Support\Facades\URL::current()" />
<x-meta-og-locale :locale="str_replace('_', '-', app()->getLocale())" />
<x-meta-og-site-name :name="config('app.name')" />
<x-meta-x-card card="summary" />
