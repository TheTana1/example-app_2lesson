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
        switch ($request->get('genre')) {
            case 'pop':
                $query->where('genre', 'pop');
                break;
            case 'rock':
                $query->where('genre', 'rock');
                break;
            case 'hip_hop':
                $query->where('genre', 'hip_hop');
                break;

            case 'rap':
                $query->where('genre', 'rap');
                break;

            case 'jazz':
                $query->where('genre', 'jazz');
                break;
            case 'blues':
                $query->where('genre', 'blues');
                break;
            case 'classical':
                $query->where('genre', 'classical');
                break;
            case 'house':
                $query->where('genre', 'house');
                break;
            case 'techno':
                $query->where('genre', 'techno');
                break;
            case 'rnb':
                $query->where('genre', 'rnb');
                break;
            case 'metal':
                $query->where('genre', 'metal');
                break;
            case 'reggae':
                $query->where('genre', 'reggae');
                break;
            case 'country':
                $query->where('genre', 'country');
                break;
            case 'soundtrack':
                $query->where('genre', 'soundtrack');
                break;
        }
        return $query;
    }

}
