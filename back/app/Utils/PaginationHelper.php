<?php

namespace App\Utils;

use Illuminate\Http\Request;

class PaginationHelper
{
    private const DEFAULT_PER_PAGE = 10;

    public static function getLimitPagination(Request $request): int
    {
        return $request->integer('perpage', self::DEFAULT_PER_PAGE);
    }

}