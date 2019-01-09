<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;

class CartController extends Controller
{
    //
    public function __construct()
    {
    }
    public function index(Request $request)
    {
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
        $score=GoodsModel::where($where)->value('score');
        if($score<=0){
            echo "库存不足";
            die;
        }
        $res = GoodsModel::where($where)->decrement('score');
        if($res){
            echo "添加成功";
        }

    }

    public function add2()
    {
        $response=[
            'error'=>0,
            'msg'=>'添加成功'
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

}
