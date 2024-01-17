# Laravel Meta

[![Novius CI](https://github.com/novius/laravel-meta/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/novius/laravel-meta/actions/workflows/main.yml)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-nova-publishable.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-nova-publishable)
[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)


## Introduction

A package to manage meta fields on Laravel Eloquent models.

## Requirements

* Laravel 10.0

## Installation

You can install the package via composer:

```bash
composer require novius/laravel-meta
```

Optionally you can also: 

```bash
php artisan vendor:publish --provider="Novius\Publishable\LaravelPublishableServiceProvider" --tag=lang
php artisan vendor:publish --provider="Novius\Publishable\LaravelPublishableServiceProvider" --tag=views
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
            $this->hasMetaConfig = new ModelConfig(
                IndexFollow::index_follow, // The default value of the seo_robots field if not defined 
                'title', // The name of field for the default value of the seo_title and og_title fields if not defined. Can also be a callable, see below
                function($model) { // The default value of the seo_description and og_description fields if not defined. Can also be a string, see above
                    return $model->description;                
                }
            );
        }

        return $this->hasMetaConfig;
    }
```

#### Extensions

```php
$post = Post::first();
$post->canBeIndexedByRobots();
$post->seo_robots;
$post->seo_title;
$post->seo_description;
$post->seo_keywords;
$post->og_title;
$post->og_description;
$post->og_image;

$postsIndexableByRobots = Post::query()->indexableByRobots();
$postsNotIndexableByRobots = Post::query()->notIndexableByRobots();
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
                'seo_keywords' => false, // This will not display field for seo_keywords 
                'required' => [
                    'seo_robots' => true, // This will set required for field seo_robots
                ],
            ])),
        ];
    }
}

```

#### Front




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
