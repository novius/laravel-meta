<?php

namespace Novius\LaravelMeta\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Tests\Models\HasMetaModel;

class HasMetaModelFactory extends Factory
{
    protected $model = HasMetaModel::class;

    public function indexFollow()
    {
        return $this->state(function (array $attributes) {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::index_follow,
                ],
            ];
        });
    }

    public function indexNoFollow()
    {
        return $this->state(function (array $attributes) {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::index_nofollow,
                ],
            ];
        });
    }

    public function noIndexFollow()
    {
        return $this->state(function (array $attributes) {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::noindex_follow,
                ],
            ];
        });
    }

    public function noIndexNoFollow()
    {
        return $this->state(function (array $attributes) {
            return [
                'meta' => [
                    'seo_robots' => IndexFollow::noindex_nofollow,
                ],
            ];
        });
    }

    public function definition()
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
        ];
    }
}
