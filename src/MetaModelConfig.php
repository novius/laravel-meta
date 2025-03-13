<?php

namespace Novius\LaravelMeta;

use Illuminate\Database\Eloquent\Model;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\Enums\OgType;
use Novius\LaravelMeta\Traits\HasMeta;

/** @phpstan-consistent-constructor */
class MetaModelConfig
{
    public IndexFollow $defaultSeoRobots;

    public OgType $defaultOgType;

    /** @var callable|string|null */
    public $fallbackTitle;

    /** @var callable|string|null */
    public $fallbackDescription;

    /** @var callable|string|null */
    public $fallbackImage;

    /** @var callable */
    public $getOgImageUrl;

    public string $ogImageDisk = 'public';

    public string $ogImagePath = '/';

    public function __construct()
    {
        $this->defaultSeoRobots = IndexFollow::index_follow;
        $this->defaultOgType = OgType::website;
        $this->getOgImageUrl = static function ($model) {
            /** @var Model&HasMeta $model */
            if ($model->og_image) {
                return asset('storage/'.$model->og_image);
            }

            return null;
        };
    }

    public function setDefaultSeoRobots(IndexFollow $defaultSeoRobots): static
    {
        $this->defaultSeoRobots = $defaultSeoRobots;

        return $this;
    }

    public function setDefaultOgType(OgType $type): static
    {
        $this->defaultOgType = $type;

        return $this;
    }

    public function setFallbackTitle($fallbackTitle): static
    {
        $this->fallbackTitle = $fallbackTitle;

        return $this;
    }

    public function setFallbackDescription($fallbackDescription): static
    {
        $this->fallbackDescription = $fallbackDescription;

        return $this;
    }

    public function setFallbackImage($fallbackImage): static
    {
        $this->fallbackImage = $fallbackImage;

        return $this;
    }

    public function setCallbackOgImageUrl(callable $callback): static
    {
        $this->getOgImageUrl = $callback;

        return $this;
    }

    public function setOgImageDisk(string $disk): static
    {
        $this->ogImageDisk = $disk;

        return $this;
    }

    public function setOgImagePath(string $path): static
    {
        $this->ogImagePath = $path;

        return $this;
    }

    public static function make(): static
    {
        return new static;
    }
}
