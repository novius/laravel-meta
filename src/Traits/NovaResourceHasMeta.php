<?php

namespace Novius\LaravelMeta\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;
use Novius\LaravelMeta\Enums\IndexFollow;

/** @phpstan-ignore trait.unused */
trait NovaResourceHasMeta
{
    public function getSEONovaFields(array $fields = []): Collection
    {
        /** @var Model&HasMeta $model */
        $model = static::newModel();
        if (! in_array(HasMeta::class, class_uses_recursive($model), true)) {
            return collect();
        }
        $columnMeta = $model->getMetaColumn();
        $metaConfig = $model->getMetaConfig();

        $options = Arr::pluck(IndexFollow::cases(), 'value', 'value');

        /** @var resource $this */
        return collect([
            'badge' => Badge::make(trans('laravel-meta::messages.badge'), function () {
                /** @var resource $this */
                if ($this->resource->seo_title && $this->resource->seo_description) {
                    return '100%';
                }
                if ($this->resource->seo_title || $this->resource->seo_description) {
                    return '50%';
                }

                return '0%';
            })->map([
                '0%' => 'danger',
                '50%' => 'warning',
                '100%' => 'success',
            ])->onlyOnIndex(),
            'seo_title' => Text::make(trans('laravel-meta::messages.seo_title'), $columnMeta.'->seo_title')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            'seo_description' => Textarea::make(trans('laravel-meta::messages.seo_description'), $columnMeta.'->seo_description')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            'seo_keywords' => Textarea::make(trans('laravel-meta::messages.seo_keywords'), $columnMeta.'->seo_keywords')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            'seo_robots' => Select::make(trans('laravel-meta::messages.seo_robots'), $columnMeta.'->seo_robots')
                ->rules('nullable')
                ->options(array_combine($options, $options))
                ->hideFromIndex(),
            'og_title' => Text::make(trans('laravel-meta::messages.og_title'), $columnMeta.'->og_title')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            'og_description' => Text::make(trans('laravel-meta::messages.og_description'), $columnMeta.'->og_description')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            'og_image' => Image::make(trans('laravel-meta::messages.og_image'), $columnMeta.'->og_image')
                ->disk($metaConfig->ogImageDisk)
                ->path($metaConfig->ogImagePath)
                ->prunable()
                ->hideFromIndex(),
        ])
            ->concat($fields)
            ->filter();
    }
}
