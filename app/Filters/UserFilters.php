<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class UserFilters
{
    public function apply(Request $request, Builder $query): Builder
    {
        if($request->has('active') && $request->get('active') != null && $request->get('active') !== '2'){
            $query->where('active','=', $request->get('active'));
        }
        if($request->has('search') && $request->get('search') != null){
            //dd($request->input());
            $query->where('name', 'like', '%'.$request->get('search').'%')
                ->orWhere('email', 'like', '%'.$request->get('search').'%');
        }
        if($request->has('date_from') && $request->get('date_from') != null){
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if($request->has('date_to') && $request->get('date_to') != null){
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        return $query;
    }
}
