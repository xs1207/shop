<?php

namespace App\Http\Middleware;

use Closure;

class CheckLoginToken
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
        if(!$request->session()->get('u_token')){
            header('Refresh:1;url=/users/login');
            echo "请先登录";
            die;
        }
        return $next($request);
    }


}
