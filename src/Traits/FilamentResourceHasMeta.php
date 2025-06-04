<?php

namespace Novius\LaravelMeta\Traits;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Novius\LaravelMeta\Enums\IndexFollow;

/**
 * @mixin Resource
 */
trait FilamentResourceHasMeta
{
    public static function getTableSEOBadgeColumn(): TextColumn
    {
        return TextColumn::make('seo_badge')
            ->label(trans('laravel-meta::messages.badge'))
            ->badge()
            ->getStateUsing(function (Model $record) {
                if ($record->seo_title && $record->seo_description) {
                    return '100%';
                }
                if ($record->seo_title || $record->seo_description) {
                    return '50%';
                }

                return '0%';
            })
            ->colors([
                '0%' => 'danger',
                '50%' => 'warning',
                '100%' => 'success',
            ])
            ->icon(fn ($state) => match ($state) {
                '100%' => 'heroicon-o-shield-check',
                default => 'heroicon-o-shield-exclamation',
            });
    }

    public static function getFormSEOFields(array $fields = []): array
    {
        $modelClass = static::getModel();

        /** @var Model&HasMeta $model */
        $model = new $modelClass;
        if (! in_array(HasMeta::class, class_uses_recursive($model), true)) {
            return collect();
        }
        $columnMeta = $model->getMetaColumn();
        $metaConfig = $model->getMetaConfig();

        $options = Arr::pluck(IndexFollow::cases(), 'value', 'value');

        return collect([
            'seo_title' => TextInput::make($columnMeta.'.seo_title')
                ->label(trans('laravel-meta::messages.seo_title'))
                ->string(),
            'seo_description' => Textarea::make($columnMeta.'.seo_description')
                ->label(trans('laravel-meta::messages.seo_description'))
                ->string(),
            'seo_keywords' => Textarea::make($columnMeta.'.seo_keywords')
                ->label(trans('laravel-meta::messages.seo_keywords'))
                ->string(),
            'seo_robots' => Select::make($columnMeta.'.seo_robots')
                ->label(trans('laravel-meta::messages.seo_robots'))
                ->options(array_combine($options, $options)),
            'og_title' => TextInput::make($columnMeta.'.og_title')
                ->label(trans('laravel-meta::messages.og_title'))
                ->string(),
            'og_description' => TextInput::make($columnMeta.'.og_description')
                ->label(trans('laravel-meta::messages.og_description'))
                ->string(),
            'og_image' => FileUpload::make($columnMeta.'.og_image')
                ->label(trans('laravel-meta::messages.og_image'))
                ->image()
                ->disk($metaConfig->ogImageDisk)
                ->directory($metaConfig->ogImagePath),
        ])
            ->concat($fields)
            ->filter()
            ->toArray();
    }
}
