<?php

namespace Novius\LaravelMeta\Enums;

enum IndexFollow: string
{
    case index_follow = 'index, follow';
    case index_nofollow = 'index, nofollow';
    case noindex_follow = 'noindex, follow';
    case noindex_nofollow = 'noindex, nofollow';
}
