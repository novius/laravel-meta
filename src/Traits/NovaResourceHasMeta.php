<?php

namespace Novius\LaravelMeta\Traits;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;
use Novius\LaravelMeta\Enums\IndexFollow;

trait NovaResourceHasMeta
{
    public function getSEONovaFields(): array
    {
        /** @var Model&HasMeta $model */
        $model = static::newModel();
        if (! in_array(HasMeta::class, class_uses_recursive($model), true)) {
            return [];
        }
        $columnMeta = $model->getMetaColumn();

        $options = array_keys(IndexFollow::cases());

        /** @var resource $this */
        return [
            Badge::make(trans('laravel-meta::messages.badge'), function () {
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
            Text::make(trans('laravel-meta::messages.seo_title'), $columnMeta.'.seo_title')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            Textarea::make(trans('laravel-meta::messages.seo_description'), $columnMeta.'.seo_description')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            Textarea::make(trans('laravel-meta::messages.seo_keywords'), $columnMeta.'.seo_keywords')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            Select::make(trans('laravel-meta::messages.seo_robots'), $columnMeta.'.seo_robots')
                ->rules('required')
                ->options(array_combine($options, $options))
                ->hideFromIndex(),
            Text::make(trans('laravel-meta::messages.og_title'), $columnMeta.'.og_title')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            Text::make(trans('laravel-meta::messages.og_description'), $columnMeta.'.og_description')
                ->rules('nullable', 'string')
                ->hideFromIndex(),
            Image::make(trans('laravel-meta::messages.og_image'), $columnMeta.'.og_image')
                ->hideFromIndex(),
        ];
    }
}
