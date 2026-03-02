<?php

namespace Novius\LaravelMeta\Tests;

use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Enums\OgType;
use Novius\LaravelMeta\Tests\Models\HasMetaModel;
use PHPUnit\Framework\Attributes\Test;

class HasMetaTest extends TestCase
{
    /* --- HasMeta Tests --- */

    #[Test]
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

    #[Test]
    public function accessor_can_be_indexed_by_robots(): void
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

    #[Test]
    public function accessor_fallbacks(): void
    {
        $model = HasMetaModel::factory()->create();
        $this->assertEquals($model->title, $model->seo_title);
        $this->assertEquals($model->title, $model->og_title);
        $this->assertEquals($model->description, $model->seo_description);
        $this->assertEquals($model->description, $model->og_description);
    }

    #[Test]
    public function setters_can_set_meta_values(): void
    {
        $model = new HasMetaModel;
        $model->seo_title = 'New SEO Title';
        $model->seo_description = 'New SEO Description';
        $model->seo_keywords = 'keyword1, keyword2';
        $model->og_title = 'New OG Title';
        $model->og_description = 'New OG Description';
        $model->og_image = 'og-image.png';

        $this->assertEquals('New SEO Title', $model->seo_title);
        $this->assertEquals('New SEO Description', $model->seo_description);
        $this->assertEquals('keyword1, keyword2', $model->seo_keywords);
        $this->assertEquals('New OG Title', $model->og_title);
        $this->assertEquals('New OG Description', $model->og_description);
        $this->assertEquals('og-image.png', $model->og_image);

        $meta = $model->meta;

        $this->assertEquals('New SEO Title', $meta['seo_title']);
        $this->assertEquals('New SEO Description', $meta['seo_description']);
        $this->assertEquals('keyword1, keyword2', $meta['seo_keywords']);
        $this->assertEquals('New OG Title', $meta['og_title']);
        $this->assertEquals('New OG Description', $meta['og_description']);
        $this->assertEquals('og-image.png', $meta['og_image']);
    }

    #[Test]
    public function setters_can_set_meta_values_with_enums(): void
    {
        $model = new HasMetaModel;
        $model->seo_robots = IndexFollow::noindex_nofollow;
        $model->og_type = OgType::article;

        $this->assertEquals(IndexFollow::noindex_nofollow->value, $model->seo_robots instanceof IndexFollow ? $model->seo_robots->value : $model->seo_robots);
        $this->assertEquals(OgType::article->value, $model->og_type instanceof OgType ? $model->og_type->value : $model->og_type);

        $meta = $model->meta;

        $this->assertEquals(IndexFollow::noindex_nofollow->value, $meta['seo_robots']);
        $this->assertEquals(OgType::article->value, $meta['og_type']);
    }
}
