<?php

namespace Novius\LaravelMeta\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Enums\OgType;
use Novius\LaravelMeta\MetaModelConfig;

/**
 * @property string|null $seo_robots
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property string|null $og_type
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property-read string|null $og_image_url
 *
 * @method static static|Builder|\Illuminate\Database\Query\Builder indexableByRobots()
 * @method static static|Builder|\Illuminate\Database\Query\Builder notIndexableByRobots()
 *
 * @phpstan-ignore trait.unused
 */
trait HasMeta
{
    public function initializeHasMeta(): void
    {
        if (! isset($this->casts[$this->getMetaColumn()])) {
            $this->casts[$this->getMetaColumn()] = 'json';
        }
    }

    protected MetaModelConfig $metaConfig;

    public function getMetaConfig(): MetaModelConfig
    {
        if (! isset($this->metaConfig)) {
            $this->metaConfig = MetaModelConfig::make();
        }

        return $this->metaConfig;
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

    protected function metaSetter(string $key): Closure
    {
        return function ($value) use ($key) {
            $metaColumn = $this->getMetaColumn();
            $meta = $this->getAttributeFromArray($metaColumn) ?? [];
            if (is_string($meta)) {
                $meta = json_decode($meta, true) ?? [];
            }
            $meta[$key] = $value;

            return [
                $metaColumn => json_encode($meta),
            ];
        };
    }

    protected function seoRobots(): Attribute
    {
        return Attribute::make(
            get: function () {
                return (IndexFollow::tryFrom(Arr::get($this->{$this->getMetaColumn()}, 'seo_robots')) ?? $this->getMetaConfig()->defaultSeoRobots)->value;
            },
            set: function ($value) {
                $value = $value instanceof IndexFollow ? $value : IndexFollow::tryFrom($value);

                $setter = $this->metaSetter('seo_robots');

                return $setter($value);
            }
        );
    }

    protected function seoTitle(): Attribute
    {
        $setter = $this->metaSetter('seo_title');

        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'seo_title') ?? $this->fallbackMeta($this->getMetaConfig()->fallbackTitle);
            },
            set: $setter
        );
    }

    protected function seoDescription(): Attribute
    {
        $setter = $this->metaSetter('seo_description');

        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'seo_description') ?? $this->fallbackMeta($this->getMetaConfig()->fallbackDescription);
            },
            set: $setter
        );
    }

    protected function seoKeywords(): Attribute
    {
        $setter = $this->metaSetter('seo_keywords');

        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'seo_keywords');
            },
            set: $setter
        );
    }

    protected function ogType(): Attribute
    {
        return Attribute::make(
            get: function () {
                return (OgType::tryFrom(Arr::get($this->{$this->getMetaColumn()}, 'og_type')) ?? $this->getMetaConfig()->defaultOgType)->value;
            },
            set: function ($value) {
                $value = $value instanceof OgType ? $value : OgType::tryFrom($value);

                $setter = $this->metaSetter('og_type');

                return $setter($value);
            }
        );
    }

    protected function ogTitle(): Attribute
    {
        $setter = $this->metaSetter('og_title');

        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'og_title') ?? $this->fallbackMeta($this->getMetaConfig()->fallbackTitle);
            },
            set: $setter
        );
    }

    protected function ogDescription(): Attribute
    {
        $setter = $this->metaSetter('og_description');

        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'og_description') ?? $this->fallbackMeta($this->getMetaConfig()->fallbackDescription);
            },
            set: $setter
        );
    }

    protected function ogImage(): Attribute
    {
        $setter = $this->metaSetter('og_image');

        return Attribute::make(
            get: function () {
                return Arr::get($this->{$this->getMetaColumn()}, 'og_image') ?? $this->fallbackMeta($this->getMetaConfig()->fallbackImage);
            },
            set: $setter
        );
    }

    protected function ogImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->og_image) {
                    return asset('storage/'.$this->og_image);
                }

                return $this->fallbackMeta($this->getMetaConfig()->getOgImageUrl);
            }
        );
    }

    public function canBeIndexedByRobots(): bool
    {
        $seo_robots = IndexFollow::tryFrom(Arr::get($this->{$this->getMetaColumn()}, 'seo_robots', $this->getMetaConfig()->defaultSeoRobots));

        if ($seo_robots === null) {
            $seo_robots = $this->getMetaConfig()->defaultSeoRobots;
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
        return defined(__CLASS__.'::META') ? constant(__CLASS__.'::META') : 'meta';
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
        $default = $this->getMetaConfig()->defaultSeoRobots;
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
        $default = $this->getMetaConfig()->defaultSeoRobots;
        if (in_array($default->value, $notIndexables, true)) {
            $builder->orWhereNull($this->getQualifiedMetaColumn().'->seo_robots');
        }
    }
}
