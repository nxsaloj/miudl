<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class prePage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pre = isset($request->pre)? $request->pre:null;
        $id = isset($request->id)? $request->id:null;

        $base = url('/');
        $url = strval($request->url());
        $urlmod = str_replace($base,'',$url);

        if($urlmod !== '/change')
        {
            if(Auth::check())
            {
                if (Auth::user()->Changed_at == null && Auth::user()->id > 1)
                {
                    return response()->view('app.change.initial');
                }
            }
        }

        return $next($request);
    }
}
