<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class RequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
//    private string $apiToken = '';
    public function handle(Request $request, Closure $next): Response
    {
//        $isSuccess =[
//            '127.0.0.1',
//            '127.0.0.2',
//            '127.0.0.3',
//            '127.0.0.4',
//        ];
//        if(!in_array($request->ip(), $isSuccess)){
//            return $next($request);
//        }
//        throw new UnauthorizedException('Вы не являетесь автором');


//        $user = User::query()->first();
//        $days = Carbon::parse($user->created_at->toDateString())->
//        diffInDays(Carbon::now()->toDateString());
//
//        if($days >= 3){
//            return redirect()->route('users.index');
//        }

//        $header = $request->header->get('Authorization');
//        if($header=== $this->apiToken){
//            return $next($request);
//        }

        return $next($request);


    }
}
