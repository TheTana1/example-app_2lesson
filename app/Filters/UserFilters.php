<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class UserFilters
{
    public function apply(Request $request, Builder $query): Builder
    {

        if($request->has('search') && $request->get('search') != null){
            dd($request->only(['search', 'date_filter', 'sort']));
            $query->where('name', 'like', '%'.$request->get('search').'%')
                ->orWhere('email', 'like', '%'.$request->get('search').'%');
        }
        if($request->has('date_filter') && $request->get('date_filter') != null){
            $query->whereDate('created_at', '>=', $request->get('date_filter'));
        }
        if($request->has('sort') && $request->get('sort') != null){

        }
        return $query;
    }
}
