<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookFilters
{
    public function apply(Request $request, Builder $query): Builder
    {
        if ($request->has('search') && $request->get('search') != null)
        {
            $query->where('title', 'like', '%'.$request->get('search').'%');
        }
        switch ($request->get('sort', 'newest'))
        {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        return $query;
    }
}
