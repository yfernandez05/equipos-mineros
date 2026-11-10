<?php

namespace App\Http\Controllers;

use App\Utils\FilterManager;
use Illuminate\Http\Request;
use App\Utils\RuleManager;
use Illuminate\Database\Eloquent\Model;

class BaseController extends Controller
{
   
    protected function getLimitPagination(Request $request, int $defaultPaginate = 10): int {
        $limit = $request->integer('perpage', $defaultPaginate);
        return max(1, min($limit, 100));
    }

    public function getFilters(Request $request, Model $model = null, $defaultFilters = [RuleManager::FIELD_NAME_STATE, '=', RuleManager::ACTIVE_STATE])
    {
       return FilterManager::getFilters($request, $model, $defaultFilters);
    }

}
