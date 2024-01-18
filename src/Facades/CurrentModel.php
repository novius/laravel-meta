<?php

namespace Novius\LaravelMeta\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Novius\LaravelMeta\Services\CurrentModelService;

/**
 * @method static void setModel(Model $model)
 * @method static ?Model getModel()
 */
class CurrentModel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CurrentModelService::class;
    }
}
