<?php

namespace Novius\LaravelMeta\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Enums\OgType;
use Novius\LaravelMeta\ModelConfig;

/**
 * @property-read string|null $seo_robots
 * @property-read string|null $seo_title
 * @property-read string|null $seo_description
 * @property-read string|null $seo_keywords
 * @property-read string|null $og_type
 * @property-read string|null $og_title
 * @property-read string|null $og_description
 * @property-read string|null $og_image
 * @property-read string|null $og_image_url
 *
 * @method static static|Builder|\Illuminate\Database\Query\Builder indexableByRobots()
 * @method static static|Builder|\Illuminate\Database\Query\Builder notIndexableByRobots()
 */
trait HasMeta
{
    public function initializeHasMeta(): void
    {
        if (! isset($this->casts[$this->getMetaColumn()])) {
            $this->casts[$this->getMetaColumn()] = 'json';
        }
    }

    protected ModelConfig $hasMetaConfig;

    public function hasMetaConfig(): ModelConfig
    {
        if (! isset($this->hasMetaConfig)) {
            $this->hasMetaConfig = ModelConfig::make();
        }

        return $this->hasMetaConfig;
    }

    protected function fallbackMeta($fallback)
    {
        if ($fallback === null) {
            return null;
        }
        if (is_callable($fallback)) {
            return $fallback($this);
        }

        return $this->getAttribute($fallback);
    }

    protected function seoRobots(): Attribute
    {
        return Attribute::make(
            get: function () {
                return IndexFollow::tryFrom(Arr::get($this->{$this->getMetaColumn()}, 'seo_robots', $this->hasMetaConfig()->defaultSeoRobots))?->value;
            }
        );
    }

    protected function seoTitle(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'seo_title', $this->fallbackMeta($this->hasMetaConfig()->fallbackTitle));
            }
        );
    }

    protected function seoDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'seo_description', $this->fallbackMeta($this->hasMetaConfig()->fallbackDescription));
            }
        );
    }

    protected function seoKeywords(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'seo_keywords');
            }
        );
    }

    protected function ogType(): Attribute
    {
        return Attribute::make(
            get: function () {
                return OgType::tryFrom(Arr::get($this->{$this->getMetaColumn()}, 'og_type', $this->hasMetaConfig()->defaultOgType))?->value;
            }
        );
    }

    protected function ogTitle(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'og_title', $this->fallbackMeta($this->hasMetaConfig()->fallbackTitle));
            }
        );
    }

    protected function ogDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'og_description', $this->fallbackMeta($this->hasMetaConfig()->fallbackDescription));
            }
        );
    }

    protected function ogImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'og_image', $this->fallbackMeta($this->hasMetaConfig()->fallbackImage));
            }
        );
    }

    protected function ogImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->fallbackMeta($this->hasMetaConfig()->getOgImageUrl);
            }
        );
    }

    public function canBeIndexedByRobots(): bool
    {
        $seo_robots = IndexFollow::tryFrom(Arr::get($this->{$this->getMetaColumn()}, 'seo_robots', $this->hasMetaConfig()->defaultSeoRobots));

        if ($seo_robots === null) {
            $seo_robots = $this->hasMetaConfig()->defaultSeoRobots;
        }

        return in_array($seo_robots, [
            IndexFollow::index_follow,
            IndexFollow::index_nofollow,
        ], true);
    }

    /**
     * Get the name of the "meta" column.
     */
    public function getMetaColumn(): string
    {
        return defined('static::META') ? static::META : 'meta';
    }

    /**
     * Get the fully qualified "meta" column.
     */
    public function getQualifiedMetaColumn(): string
    {
        return $this->qualifyColumn($this->getMetaColumn());
    }

    public function scopeIndexableByRobots(Builder $builder): void
    {
        $indexables = [
            IndexFollow::index_follow->value,
            IndexFollow::index_nofollow->value,
        ];
        $builder->whereIn($this->getQualifiedMetaColumn().'->seo_robots', $indexables);
        $default = $this->hasMetaConfig()->defaultSeoRobots;
        if (in_array($default->value, $indexables, true)) {
            $builder->orWhereNull($this->getQualifiedMetaColumn().'->seo_robots');
        }
    }

    public function scopeNotIndexableByRobots(Builder $builder): void
    {
        $notIndexables = [
            IndexFollow::noindex_follow->value,
            IndexFollow::noindex_nofollow->value,
        ];
        $builder->whereIn($this->getQualifiedMetaColumn().'->seo_robots', $notIndexables);
        $default = $this->hasMetaConfig()->defaultSeoRobots;
        if (in_array($default->value, $notIndexables, true)) {
            $builder->orWhereNull($this->getQualifiedMetaColumn().'->seo_robots');
        }
    }
}
