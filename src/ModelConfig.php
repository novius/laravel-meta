<?php

namespace Novius\LaravelMeta;

use Novius\LaravelMeta\Enums\IndexFollow;

class ModelConfig
{
    /**
     * @param  callable|string  $fallbackTitle
     * @param  callable|string  $fallbackDescription
     */
    public function __construct(public IndexFollow $defaultSeoRobot = IndexFollow::index_follow, public $fallbackTitle = null, public $fallbackDescription = null)
    {
    }
}
