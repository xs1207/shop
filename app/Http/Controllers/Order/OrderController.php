<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\OrderModel;

use GuzzleHttp\Client;

class OrderController extends Controller
{
    //
    public function index()
    {
        echo __METHOD__;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 下单
     */
    public function add(Request $request)
    {

        $cart_goods=CartModel::where(['uid'=>session()->get('uid')])->orderBy('id','desc')->get()->toArray();
//        print_r($cart_goods);die;
        if(empty($cart_goods)){
            header("Refresh:1;url=/goods/list");
            die("购物车中无商品");
        }
        $goods_id=[];
        $num=[];
        $order_amount=0;
        foreach($cart_goods as $k=>$v){
            $goods_id[]=$v['goods_id'];
            $num[]=$v['num'];
            $goodsInfo=GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goodsInfo['num']=$v['num'];
            $list[]=$goodsInfo;
            //计算订单价格 = 商品数量*单价
            $order_amount+=$goodsInfo['price']*$v['num'];
        }
        //生成订单号
        $goods_id=implode(',',$goods_id);
        $num=implode(',',$num);
        $order_sn = OrderModel::generateOrderSN();
//        echo $order_sn;echo "</br>";
        $data=[
            'order_sn'=>$order_sn,
            'uid'=>session()->get('uid'),
            'add_time'=>time(),
            'order_amount'=>$order_amount,
            'goods_id'=>$goods_id,
            'goods_num'=>$num
        ];
//        print_r($data);die;
        $oid = OrderModel::insertGetId($data);
        if(!$oid){
            echo "下单失败";
        }
        echo '下单成功,订单号：'.$order_sn;
        header("Refresh:1;url=/order/list");

        //清空购物车
        CartModel::where(['uid'=>session()->get('uid')])->delete();
    }


    /**
     * 订单号展示
     */
    public function orderList(Request $request)
    {
        $list=OrderModel::orderBy('order_id','desc')->get()->toArray();
//        print_r($list);die;
        $data=['list'=>$list];
        return view('order.list',$data);
    }

    /**
     * 分布式 测试
     */

    public function pay()
    {
        $url='http://root.tactshan.com';
        $client=new Client([
            'base_uri'=>$url,
            'timeout'=>2.0,
        ]);

        $response=$client->request('GET','/order.php');
        echo $response->getBody();
    }



}
