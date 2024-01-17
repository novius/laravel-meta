<?php

namespace Novius\LaravelMeta\Tests\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Novius\LaravelMeta\Traits\HasMeta;

class HasMetaModel extends Model
{
    use HasFactory;
    use HasMeta;
    use HasTimestamps;

    protected $table = 'has_meta_models';

    protected $guarded = [];
}
