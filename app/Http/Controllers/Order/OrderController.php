<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\OrderModel;

class OrderController extends Controller
{
    //
    public function index()
    {
        echo __METHOD__;
    }

    /**
     * 下单
     */
    public function add(Request $request)
    {

        $cart_goods=CartModel::where(['uid'=>session()->get('uid')])->orderBy('id','desc')->get()->toArray();
//        print_r($cart_goods);die;
        if(empty($cart_goods)){
            die("购物车中无商品");
        }
        $order_amount=0;
        foreach($cart_goods as $k=>$v){
            $goods_info=GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goods_info['num']=$v['num'];
            $cid=$v['id'];
            $list[]=$goods_info;

            //计算订单价格  商品数量*单价
            $order_amount+=$goods_info['price']*$v['num'];
        }

        //生成订单号
        $order_sn = OrderModel::generateOrderSN();
//        echo $order_sn;echo "</br>";
        $data=[
            'order_sn'=>$order_sn,
            'uid'=>session()->get('uid'),
            'add_time'=>time(),
            'order_amount'=>$order_amount
        ];
//        print_r($data);die;
        $oid = OrderModel::insertGetId($data);
        if(!$oid){
            echo "下单失败";
        }
        echo '下单成功,订单号：'.$order_sn;
        header("Refresh:1;url=/order/list");

        //清空购物车
        CartModel::where(['uid'=>session()->get('uid'),'id'=>$cid])->delete();
    }


    /**
     * 订单号展示
     */
    public function orderList(Request $request)
    {
        $list=OrderModel::all()->toArray();
        $data=['list'=>$list];
        return view('order.list',$data);
    }

}
