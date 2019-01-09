<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;

class CartController extends Controller
{
    //
    public $uid;           //登录ID

    public function __construct()
    {
        $this->middleware(function($request,$next){
            $this->uid=session()->get('uid');
            return $next($request);
        });
    }
    public function index(Request $request)
    {
/*
        $goods=session()->get('cart_goods');
        if(empty($goods)){
            echo "购物车是空的";
        }else{
            foreach($goods as $k=>$v){
                echo 'goods ID: '.$v;echo'</br>';
                $detail=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
                echo '<pre>';print_r($detail);echo "</pre>";
            }
        }
*/

        $cart_goods=CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if(empty($cart_goods)){
            die("购物车是空的");
        }
        if($cart_goods){
            //获取商品最新信息
            foreach($cart_goods as $k=>$v){
                $goods_info=GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $goods_info['num']=$v['num'];
                $list[]=$goods_info;
            }
        }

        $data=['list'=>$list];
        return view('cart.index',$data);
    }

    /**
     * 添加商品
     */
    public function add($goods_id)
    {
        $cart_goods=session()->get('cart_goods');
        //是否已在购物车中
        if(!empty($cart_goods)){
            if(in_array($goods_id,$cart_goods)){
                echo "已存在购物车中";
                die;
            }
        }
        session()->push('cart_goods',$goods_id);
        //减少库存
        $where=['goods_id'=>$goods_id];
        $store=GoodsModel::where($where)->value('store');
        if($store<=0){
            echo "库存不足";
            die;
        }
        $res = GoodsModel::where($where)->decrement('store');
        if($res){
            echo "添加成功";
        }

    }

    /**
     * 购物车添加商品
     * @return array
     */
    public function add2(Request $request)
    {
        $goods_id = $request->input('goods_id');
        $num = $request->input('num');


        //检查库存
        $store_num = GoodsModel::where(['goods_id'=>$goods_id])->value('store');
        if($store_num<=0){
            $response = [
                'errno' => 5001,
                'msg'   => '库存不足'
            ];
            return $response;
        }

        //检测购物车重复商品
        $cart_goods=CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if($cart_goods){
            $goods_id_arr=array_column($cart_goods,'goods_id');

            if(in_array($goods_id,$goods_id_arr)){
                $response=[
                    'erron'=>5002,
                    'msg'=>"购物车已有此商品，请勿重复添加"
                ];
                return $response;
            }
        }

        //写入购物车表
        $data = [
            'goods_id'  => $goods_id,
            'num'       => $num,
            'add_time'  => time(),
            'uid'       => session()->get('uid'),
            'session_token' => session()->get('u_token')
        ];

//        print_r($data);
//        die;
        $cid = CartModel::insertGetId($data);
        if(!$cid){
            $response = [
                'errno' => 5002,
                'msg'   => '添加购物车失败，请重试'
            ];
            return $response;
        }


        $response = [
            'error' => 0,
            'msg'   => '添加成功'
        ];
        return $response;
    }
    /**
     * 删除商品
     */
    public function del($goods_id)
    {
        $goods=session()->get('cart_goods');
//        echo '<pre>';print_r($goods);echo '</pre>';die;

        if(in_array($goods_id,$goods)){
            //执行删除操作
            foreach($goods as $k=>$v){
                if($goods_id==$v){
                    session()->pull('cart_goods.'.$k);
                }
            }
            echo "删除成功";
        }else{
            die("购物车无此商品");
        }
    }

    /**
     * 删除商品
     * 2019年1月9日15:28:46
     * @param $goods_$abc 商品ID
     */
    public function del2($goods_id)
    {
        $res=CartModel::where(['uid'=>$this->uid,'goods_id'=>$goods_id])->delete();
        if($res){
            echo '商品ID ：'.$goods_id."删除成功";
        }else{
            echo '商品ID ：'.$goods_id."删除成功2";
        }
    }

}
