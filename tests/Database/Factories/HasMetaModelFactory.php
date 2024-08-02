<?php

namespace Novius\LaravelMeta\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Tests\Models\HasMetaModel;

class HasMetaModelFactory extends Factory
{
    protected $model = HasMetaModel::class;

    public function indexFollow(): HasMetaModelFactory
    {
        return $this->state(function () {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::index_follow,
                ],
            ];
        });
    }

    public function indexNoFollow(): HasMetaModelFactory
    {
        return $this->state(function () {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::index_nofollow,
                ],
            ];
        });
    }

    public function noIndexFollow(): HasMetaModelFactory
    {
        return $this->state(function () {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::noindex_follow,
                ],
            ];
        });
    }

    public function noIndexNoFollow(): HasMetaModelFactory
    {
        return $this->state(function () {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::noindex_nofollow,
                ],
            ];
        });
    }

    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
        ];
    }
}
