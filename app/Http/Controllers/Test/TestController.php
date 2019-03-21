<?php

namespace App\Http\Controllers\Test;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class TestController extends Controller
{
    //

	public $opensslPrivKey = './key/openssl_priv.key';
	public $opensslPubKey = './key/openssl_pub.key';

    public function abc()
    {
        var_dump($_POST);echo '</br>';
        var_dump($_GET);echo '</br>';
    }

	public function world1()
	{
		echo __METHOD__;
	}


	public function hello2()
	{
		echo __METHOD__;
		header('Location:/world2');
	}

	public function world2()
	{
		echo __METHOD__;
	}

	public function md($m,$d)
	{
		echo 'm: '.$m;echo '<br>';
		echo 'd: '.$d;echo '<br>';
	}

	public function showName($name=null)
	{
		var_dump($name);
	}

	public function query1()
	{
		$list = DB::table('p_users')->get()->toArray();
		echo '<pre/>';print_r($list);echo '</pre>';
	}

	public function query2()
	{
		$user = DB::table('p_users')->where('uid', 3)->first();
		echo '<pre/>';print_r($user);echo '</pre>';echo '<hr>';
		$email = DB::table('p_users')->where('uid', 4)->value('email');
		var_dump($email);echo '<hr>';
		$info = DB::table('p_users')->pluck('age', 'name')->toArray();
		echo '<pre/>';print_r($info);echo '</pre>';


	}

	public function viewTest1()
    {
        $data=[];
        return view('test.index',$data);
    }

    public function viewTest2()
    {
        $list=UserModel::all()->toArray();
        $data=[
            'title'=>"勇士三连冠",
            'list'=>$list
        ];
        return view('test.child',$data);
    }

	public function checkCookie()
	{
		echo __METHOD__;
	}

	public function mid1()
	{
		echo __METHOD__;
	}

	public function curl()
	{
		echo "<pre>";print_r($_GET);echo "</pre>";
		echo "<pre>";print_r($_POST);echo "</pre>";
		echo "<pre>";print_r($_FILES);echo "</pre>";
	}

	public function encrpt()
	{
		$method = 'AES-128-CBC';
		$key = "password";
		$salt = "asdasd";
		$iv = substr(md5($_GET['t']. $salt),5,16);       //初始化向量  固定字节  16位
		$json_str = base64_decode($_POST['data']);
		$dec_data = openssl_decrypt($json_str,$method,$key,OPENSSL_RAW_DATA,$iv);
		$json_data=json_decode($dec_data) ;

		if(!empty($json_data)){
			$time=time();
			$response=[
				'errno'=>0,
				'msg'=>'ok'
			];
			$iv2=substr(md5($time .$salt),5,16);
			$enc_data=openssl_encrypt(json_encode($response),$method,$key,OPENSSL_RAW_DATA,$iv2);
//			var_dump($enc_data);die;
			$base_data=base64_encode($enc_data);
			//echo $base_data;die;
			$n_time=[
				't'=>$time,
				'data'=>$base_data
			];
			echo json_encode($n_time);
		}
	}

	public function sign()
	{
		$data=[
			'name'=>'徐士浩',
			'sex'=>'男',
			'age'=>'63'
		];
		$json_data=json_encode($data);
		$privateKey = file_get_contents($this->opensslPrivKey);
		$res=openssl_get_privatekey($privateKey);
		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

		openssl_sign($json_data, $sign, $res, OPENSSL_ALGO_SHA256);
		$sign=base64_encode($sign);
		$info=[
				"data"=>$data,
				'sign'=>$sign
		];
		echo json_encode($info);
	}
	public function pub()
	{
//		echo "<pre>";print_r($_POST);echo"</pre>";die;
		$sign=$_POST['sign'];
		$data=$_POST['data'];
//		var_dump($sign);
//		var_dump($data);die;

		//读取公钥文件
		$pubKey=file_get_contents($this->opensslPubKey);
		$res=openssl_get_publickey($pubKey);
		($res) or die('您使用的公钥格式错误，请检查RSA公钥配置');
		//调用openssl内置方法验签，返回bool值
		$result = (openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256));
		openssl_free_key($res);
		var_dump($result);
	}

	public  function  application(Request $request){
		$username=$request->input("username");
		if($username){
			$response=[
					'error'=>0,
					'msg'=>"数据已经收到:".$username
			];
		}else{
			$response=[
					'error'=>5001,
					'msg'=>"数据未收到".$username
			];
		}
		return $response;
	}

	public function hhb()
	{
		$data=$_POST;
		$data=json_encode($data);
		if(!empty($data)){
			$res=[
				'errno' =>0,
				'msg'=>$data
			];
		}
		return $res;
	}

	/**
	 * 接口测试注册
	 */
	public function reg(Request $request)
	{
		$rpwd=$request->input('rpwd');
		$rpwd1=$request->input('rpwd1');
		if($rpwd!==$rpwd1){
			die("密码不一致");
		}

		$pwd=password_hash($rpwd1,PASSWORD_BCRYPT);
		$data=[
				'name'=>$request->input('rname'),
				'pwd'=>$pwd,
				'email'=>$request->input('remail'),
				'reg_time'  => time(),
		];
		$u=UserModel::where(['name'=>$request->input('rname')])->first();
		if($u){
			$response=[
					'erron'=>50001,
					'msg'=>	"用户名已存在"
			];
		}else{
			$uid=UserModel::insertGetId($data);
			//var_dump($uid);
			if($uid){
				setcookie('name',$uid,time()+86400,'/','',false,true);
				$response=[
						'erron'=>0,
						'msg'=>	"注册成功"
				];
			}else{
				$response=[
						'erron'=>50002,
						'msg'=>	"注册失败"
				];
			}
		}
		return $response;

	}

	/**
	 * @param Request $request
	 * @return 接口测试登录
	 */
	public function login(Request $request)
	{
		$name=$request->input('name');
		$pwd=$request->input('pwd');

		$res=UserModel::where(['name'=>$name])->first();
		if($res){
			if(password_verify($pwd,$res->pwd)){

				$token = substr(md5(time().mt_rand(1,99999)),10,10);
//				echo $token;die;
				setcookie('uid',$res->uid,time()+86400,'/','',false,true);
				setcookie('name',$res->name,time()+86400,'/','',false,true);
				setcookie('token',$token,time()+86400,'/users','',false,true);

				$request->session()->put('u_token',$token);
				$request->session()->put('uid',$res->uid);
				$response=[
					'erron'=>0,
					'msg'=>	"登陆成功"
				];
			}else{
				$response=[
					'erron'=>500,
					'msg'=>	"登陆失败"
				];
			}
		}else{
			$response=[
				'erron'=>500,
				'msg'=>	"登陆失败"
			];
		}
		return $response;
	}

}