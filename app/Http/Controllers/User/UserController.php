<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;

class UserController extends Controller
{
	//

	public function user($uid)
	{
		echo $uid;
	}

	public function test()
	{
		echo '<pre>';
		print_r($_GET);
		echo '</pre>';
	}

	public function add()
	{
		$data = [
				'name' => str_random(5),
				'age' => mt_rand(20, 99),
				'email' => str_random(6) . '@gmail.com',
				'reg_time' => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}

	/**
	 * 用户注册
	 * 2019年1月3号
	 *杜凯龙
	 */
	public function reg()
	{
		return view('user.reg');
	}

	public function doReg(Request $request)
	{
		/*echo __METHOD__;
		echo '<pre>';print_r($_POST);'</pre>';*/
		$upwd=$request->input('upwd');
		$upwd1=$request->input('upwd1');
		if($upwd!==$upwd1){
			die("密码不一致");
		}

		$pwd=password_hash($upwd1,PASSWORD_BCRYPT);
		$data=[
				'name'=>$request->input('uname'),
				'pwd'=>$pwd,
				'age'=>$request->input('uage'),
				'email'=>$request->input('uemail'),
				'reg_time'  => time(),
		];
		$u=UserModel::where(['name'=>$request->input('uname')])->first();
		if($u){
			echo "用户名已存在";
			header("refresh:1;/users/reg");
		}else{
			$uid=UserModel::insertGetId($data);
			//var_dump($uid);
			if($uid){
				setcookie('name',$uid,time()+86400,'/','',false,true);
				header("Refresh:1;url=/users/center");
				echo '注册成功,正在跳转';
			}else{
				echo "注册失败";
				header('refresh:1;/users/reg');
			}
		}

	}
	/**
	 * 登录
	 * 2019.1.3
	 * 杜凯龙
	 */
	public function login()
	{
		return view('user.login');
	}

	public function dologin(Request $request)
	{
		$uname=$request->input('uname');
		$upwd=$request->input('upwd');

		$res=UserModel::where(['name'=>$uname])->first();
		if($res){
			if(password_verify($upwd,$res->pwd)){

				$token = substr(md5(time().mt_rand(1,99999)),10,10);
//				echo $token;die;
				setcookie('uid',$res->uid,time()+86400,'/','',false,true);
				setcookie('name',$res->name,time()+86400,'/','',false,true);
				setcookie('token',$token,time()+86400,'/users','',false,true);

				$request->session()->put('u_token',$token);
				$request->session()->put('uid',$res->uid);
				echo "登陆成功";
				header("refresh:1;/goods/list");
			}else{
				echo "账号或密码有误";
				header("refresh:1;/users/login");
			}
		}else{
			echo "账号或密码有误";
			header("refresh:1;/users/login");
		}
	}

	public function center(Request $request)
	{
		if(!empty($_COOKIE['token'])){
			if($request->session()->get('u_token')!=$_COOKIE['token']){
				header("Refresh:3;url=/userlogin");
				die("非法请求");
			}
		}


	/*
		echo 'u_token: '.$request->session()->get('u_token');echo '</br>';
		echo '<pre>';print_r($_COOKIE);echo '</pre>';
		die;
	*/

		if(empty($_COOKIE['uid'])){
			header('Refresh:1;url=/users/login');
			echo "请先登录";
			die;
		}else{
			echo 'NAME:'.$_COOKIE['name'].'欢迎回来';
			$list=UserModel::all()->toArray();
			$data=['list'=>$list];
			return view('user.center',$data);

		}
	}


	/**
	 * 用户退出
	 */
	public function logou()
	{
		setcookie("name",null);
		setcookie("uid",null);
		setcookie("token",null);
		request()->session()->pull('uid',null);
		request()->session()->pull('u_token',null);
		echo "已退出登录";
		header("Refresh:1;url=/users/login");

	}
}

