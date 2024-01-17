<?php

namespace Novius\LaravelMeta\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Novius\LaravelMeta\Services\ModelHasMetaService;

/**
 * @method static void setModel(Model $model)
 * @method static ?Model getModel()
 * @method static renderMeta(?Model $model = null)
 */
class ModelHasMeta extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ModelHasMetaService::class;
    }
}
