<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MusicFilters
{

    public function apply(Request $request, Builder $query): Builder
    {
        //dd($request->artist, $query->where('artists', $request->get('artist')));
        if ($request->has('title') && $request->get('title') != null) {
            $query->where('title', 'like', '%' . $request->get('title') . '%');
        }
        if ($request->has('artist') && $request->get('artist') != null) {
            $query->where('artists', 'like', '%' . $request->artist . '%');
        }
        if($request->has('genre') && $request->get('genre') != null)
            $query->where('genre',$request->get('genre'));


        return $query;
    }

}
