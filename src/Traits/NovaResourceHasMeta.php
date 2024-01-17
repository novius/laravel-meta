<?php

namespace Novius\LaravelMeta\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;
use Novius\LaravelMeta\Enums\IndexFollow;

trait NovaResourceHasMeta
{
    public function getSEONovaFields(array $config = []): array
    {
        /** @var Model&HasMeta $model */
        $model = static::newModel();
        if (! in_array(HasMeta::class, class_uses_recursive($model), true)) {
            return [];
        }
        $columnMeta = $model->getMetaColumn();

        $options = array_keys(IndexFollow::cases());

        /** @var resource $this */
        $fields = [];
        if (Arr::get($config, 'badge', true)) {
            $fields[] = Badge::make(trans('laravel-meta::messages.badge'), function () {
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
            ])->onlyOnIndex();
        }
        if (Arr::get($config, 'seo_title', true)) {
            $fields[] = Text::make(trans('laravel-meta::messages.seo_title'), $columnMeta.'.seo_title')
                ->rules(Arr::get($config, 'required.seo_title', false) ? 'required' : 'nullable', 'string')
                ->hideFromIndex();
        }
        if (Arr::get($config, 'seo_description', true)) {
            $fields[] = Textarea::make(trans('laravel-meta::messages.seo_description'), $columnMeta.'.seo_description')
                ->rules(Arr::get($config, 'required.seo_description', false) ? 'required' : 'nullable', 'string')
                ->hideFromIndex();
        }
        if (Arr::get($config, 'seo_keywords', true)) {
            $fields[] = Textarea::make(trans('laravel-meta::messages.seo_keywords'), $columnMeta.'.seo_keywords')
                ->rules(Arr::get($config, 'required.seo_keywords', false) ? 'required' : 'nullable', 'string')
                ->hideFromIndex();
        }
        if (Arr::get($config, 'seo_robots', true)) {
            $fields[] = Select::make(trans('laravel-meta::messages.seo_robots'), $columnMeta.'.seo_robots')
                ->rules(Arr::get($config, 'required.seo_robots', false) ? 'required' : 'nullable')
                ->options(array_combine($options, $options))
                ->hideFromIndex();
        }
        if (Arr::get($config, 'og_title', true)) {
            $fields[] = Text::make(trans('laravel-meta::messages.og_title'), $columnMeta.'.og_title')
                ->rules(Arr::get($config, 'required.og_title', false) ? 'required' : 'nullable', 'string')
                ->hideFromIndex();
        }
        if (Arr::get($config, 'og_description', true)) {
            $fields[] = Text::make(trans('laravel-meta::messages.og_description'), $columnMeta.'.og_description')
                ->rules(Arr::get($config, 'required.og_description', false) ? 'required' : 'nullable', 'string')
                ->hideFromIndex();
        }
        if (Arr::get($config, 'og_image', true)) {
            $fields[] = Image::make(trans('laravel-meta::messages.og_image'), $columnMeta.'.og_image')
                ->hideFromIndex();
        }

        return $fields;
    }
}
