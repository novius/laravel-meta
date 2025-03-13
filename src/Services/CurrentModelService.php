<?php

namespace Novius\LaravelMeta\Services;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Novius\LaravelMeta\Traits\HasMeta;

class CurrentModelService
{
    public ?Model $model = null;

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function renderMeta(?Model $model = null): View|Application|Factory|string|\Illuminate\Contracts\Foundation\Application
    {
        if ($model || $this->model) {
            $model = $model ?? $this->model;
            if (in_array(HasMeta::class, class_uses_recursive($model), true)) {
                /** @var Model&HasMeta $model */

                /** @phpstan-ignore argument.type */
                return view('laravel-meta::meta', [
                    'robots' => $model->seo_robots,
                    'title' => $model->seo_title,
                    'description' => $model->seo_description,
                    'keywords' => $model->seo_keywords,
                    'og_title' => $model->og_title,
                    'og_description' => $model->og_description,
                    'og_image' => $model->og_image,
                ]);
            }
        }

        return '';
    }
}
