<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WxUserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class KsController extends Controller
{
    public $redis_weixin_access_token_key="str:weixin:access_token";    //w微信 access_token

    //获取access_token
    public function getAccessToken()
    {
        //获取缓存
        $access_token=Redis::get($this->redis_weixin_access_token_key);
        if(!$access_token){
            print_r($access_token);
            $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
            $data=json_decode(file_get_contents($url),true);
            $access_token=$data['access_token'];
            //写入缓存
            Redis::set($this->redis_weixin_access_token_key,$access_token);
            //设置过期时间
            Redis::setTimeout($this->redis_weixin_access_token_key,3600);
        }
        return $access_token;

    }

    /**
     * 获取用户信息
     */
    public function getUserInfo($openid)
    {
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data=json_decode(file_get_contents($url),true);
        echo "<pre>";print_r($data);echo "</pre>";
    }

    /**
     * 获取用户列表
     */
    public function userList()
    {
        $list=WxUserModel::paginate(3);
        $data=['list'=>$list];

        return view('weixin.userlist',$data);
    }

    /**
     * 黑名单
     */
    public function black($id)
    {
        $res=WxUserModel::where(['id'=>$id])->update(["black"=>2]);
        if($res){
            echo "加入黑名单成功";
            header("refresh:1;url=/weixin/ks/userlist");
        }

    }


    /**
     * 获取微信用户标签
     */
    public function getWxTags()
    {
        $url='https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->getAccessToken();
        $data=json_decode(file_get_contents($url),true);
        var_dump($data);
        echo "<pre>";print_r($data);echo "</pre>";
    }


    /**
     * 创建微信用户标签
     */
    public function createWxTag($name=null)
    {
        $url='https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->getAccessToken();
        $client=new Client();
        $data=[
            'tag'=>[
                'name'=>'用户标签'
            ]
        ];
        $r = $client->request('POST', $url, [
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $r->getBody();
    }




}
