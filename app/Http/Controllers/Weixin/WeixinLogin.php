<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeixinLogin extends Controller
{
    public function WeixinLogin()
    {
        return view ('weixin.login');
    }

    public function getCode()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
        $code = $_GET['code'];
        echo 'code: '.$code;

        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx9625aa26bdfbf1bd&secret=3dd12719e18a42a07dabdf9ba62e5195&code='.$code.'&grant_type=authorization_code';
        $token_json = file_get_contents($token_url);
        $token_arr = json_decode($token_json,true);
        echo '<hr>';
        echo '<pre>';print_r($token_arr);echo '</pre>';

        $access_token = $token_arr['access_token'];
        $openid = $token_arr['openid'];

        // 3 携带token  获取用户信息
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_json = file_get_contents($user_info_url);

        $user_arr = json_decode($user_json,true);
        echo '<hr>';
        echo '<pre>';print_r($user_arr);echo '</pre>';
    }
}
