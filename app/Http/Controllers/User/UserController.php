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

		$data=[
				'name'=>$request->input('uname'),
				'pwd'=>md5($request->input('upwd')),
				'age'=>$request->input('uage'),
				'email'=>$request->input('uemail'),
				'reg_time'  => time(),
		];
		$uid=UserModel::insertGetId($data);
		//var_dump($uid);
		if($uid){
			echo "注册成功";
			header("refresh:1;/userlogin");
		}else{
			echo "注册失败";
			header('refresh:1;/register');
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
		$upwd=md5($request->input('upwd'));
		$where=[
			'name'=>$uname,
			'pwd'=>$upwd,
		];
		$res=UserModel::where($where)->first();
		if($res){
			echo "登陆成功";
			header("refresh:1;/test/test2");
		}else{
			echo "账号或密码错误";
			header("refresh:1;/userlogin");
		}
	}
}

