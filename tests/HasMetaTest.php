<?php

namespace Novius\LaravelMeta\Tests;

use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Tests\Models\HasMetaModel;

class HasMetaTest extends TestCase
{
    /* --- HasMeta Tests --- */

    /** @test */
    public function all_models_can_be_found_with_scopes(): void
    {
        HasMetaModel::factory()->indexFollow()->create();
        HasMetaModel::factory()->indexNoFollow()->create();
        HasMetaModel::factory()->noIndexFollow()->create();
        HasMetaModel::factory()->noIndexNoFollow()->create();
        HasMetaModel::factory()->create();

        $this->assertCount(5, HasMetaModel::all());
        $this->assertCount(3, HasMetaModel::indexableByRobots()->get());
        $this->assertCount(2, HasMetaModel::notIndexableByRobots()->get());
    }

    /** @test */
    public function accessor_canBeIndexedByRobots(): void
    {
        $model = HasMetaModel::factory()->indexFollow()->create();
        $this->assertTrue($model->canBeIndexedByRobots());

        $model->meta = ['seo_robots' => IndexFollow::index_follow];
        $model->save();
        $this->assertTrue($model->canBeIndexedByRobots());

        $model->meta = ['seo_robots' => IndexFollow::index_nofollow];
        $model->save();
        $this->assertTrue($model->canBeIndexedByRobots());

        $model->meta = ['seo_robots' => IndexFollow::noindex_follow];
        $model->save();
        $this->assertFalse($model->canBeIndexedByRobots());

        $model->meta = ['seo_robots' => IndexFollow::noindex_nofollow];
        $model->save();
        $this->assertFalse($model->canBeIndexedByRobots());
    }

    /** @test */
    public function accessor_fallbacks(): void
    {
        $model = HasMetaModel::factory()->create();
        $this->assertEquals($model->title, $model->seo_title);
        $this->assertEquals($model->title, $model->og_title);
        $this->assertEquals($model->description, $model->seo_description);
        $this->assertEquals($model->description, $model->og_description);
    }
}
