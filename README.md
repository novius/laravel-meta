# Laravel Meta

[![Novius CI](https://github.com/novius/laravel-meta/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/novius/laravel-meta/actions/workflows/main.yml)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-meta.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-meta)
[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)


## Introduction

A package to manage meta fields on Laravel Eloquent models.

## Requirements

* PHP >= 8.2
* Laravel 10.0

## Installation

You can install the package via composer:

```bash
composer require novius/laravel-meta
```

Optionally you can also: 

```bash
php artisan vendor:publish --provider="Novius\LaravelMeta\LaravelMetaServiceProvider" --tag=lang
php artisan vendor:publish --provider="Novius\LaravelMeta\LaravelMetaServiceProvider" --tag=views
```

## Usage

#### Migrations

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('text');
    $table->timestamps();
    $table->addMeta(); // Macro provided by the package
});
```

#### Eloquent Model Trait

```php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use Novius\LaravelMeta\Traits\HasMeta;

class Post extends Model {
    use HasMeta;
    ...
}
```

You can also add this method which will define the default operation of the trait 

```php

    public function hasMetaConfig(): ModelConfig
    {
        if (! isset($this->hasMetaConfig)) {
            $this->hasMetaConfig = ModelConfig::make()
                ->setDefaultSeoRobots(IndexFollow::index_follow) // The default value of the seo_robots field if not defined
                ->setFallbackTitle('title') // The name of field for the default value of the seo_title and og_title fields if not defined. Can also be a callable, see below
                ->setFallbackDescription(function($model) { // The default value of the seo_description and og_description fields if not defined. Can also be a string, see above
                    return $model->description;                
                })
                ->setFallbackImage('picture')
                ->setCallbackOgImageUrl(function($model) { // The function to get the og_image url
                    if ($model->og_image) {
                        return asset('storage/'.$model->og_image);
                    }
        
                    return null;
                });
        }

        return $this->hasMetaConfig;
    }
```

#### Extensions

```php
$model = ModelHasMeta::first();
$model->canBeIndexedByRobots();
$model->seo_robots
$model->seo_title
$model->seo_description
$model->seo_keywords
$model->og_type
$model->og_title
$model->og_description
$model->og_image
$model->og_image_url

$indexableByRobots = Post::query()->indexableByRobots();
$notIndexableByRobots = Post::query()->notIndexableByRobots();
```

#### Nova

If you use Laravel Nova, you can do that on your Resource on a Model using HasMeta :

```php
<?php

use Novius\LaravelMeta\Traits\NovaResourceHasMeta;

class HasMetaModel extends Resource
{
    use NovaResourceHasMeta;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            new Panel('Model', [
                Text::make('Title', 'title'),
                Textarea::make('Description', 'description'),
            ]),
            new Panel('Meta', $this->getSEONovaFields([
                'seo_keywords' => null, // This will not display field for seo_keywords 
                'og_image' => AlternativeImageField::make(trans('laravel-meta::messages.og_image'), $columnMeta.'->og_image')
                    ->hideFromIndex(),
            ])),
        ];
    }
}

```

#### Front

You can use de Facade CurrentModel to display the meta of a model.

In your controller :

```php
use Novius\LaravelMeta\Facades\CurrentModel;

class HasModelController extends Controller
{
    public function show($id)
    {
        $model = HasMetaModel::find($id);
        CurrentModel::setModel($model);

        return view('has-meta', compact('model'));
    }
}
```

In the view :

```php
@section('metas')
    @include('laravel-meta::meta')
@endsection

<x-main-layout>
    <div class="container mx-auto px-4 md:px-0">
        <h1>{{ $model->title }}</h1>
        <p>{{ $model->description }}</p>
    </div>
</x-main-layout>
```

### Testing

```bash
composer run test
```

## CS Fixer

Lint your code with Laravel Pint using:

```bash
composer run cs-fix
```

## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
