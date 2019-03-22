<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Redis;
class CheckCookie
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
        if(isset($_COOKIE['uid']) && isset($_COOKIE['token'])){
            //验证 token
            $key="str:u:token:".$_COOKIE['uid'];
            $token=redis::get($key);
            var_dump($token);
            var_dump($_COOKIE['token']);die;
            if($_COOKIE['token']==$token){
                //token  有效
                $request->attributes->add(['is_login'=>1]);
            }else{
                //token 无效
                $request->attributes->add(['is_login'=>0]);
            }
        }else{
            //未登录
            $request->attributes->add(['is_login'=>0]);
        }
        return $next($request);
    }
}
