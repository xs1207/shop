<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\OrderModel;

class IndexController extends Controller
{
    //
    public function order($order_id)
    {
        //查询订单
        $order_info=OrderModel::where(['order_id'=>$order_id])->first();
        if(!$order_info){
            die("订单：".$order_id."不存在") ;
        }

        //检查订单状态  是否已支付  已过期  已删除
        if($order_info->pay_time>0){
            die("此订单已支付 ，无法再次被支付");
        }

        //调起支付宝支付


        //支付成功修改支付时间
        $data=[
            'pay_time'=>time(),
            'pay_amount'=>rand(1111,9999),
            'is_pay'=>1
        ];
        OrderModel::where(["order_id"=>$order_id])->update($data);

        //增加消费积分
        header('Refresh:1;url=/users/center');
        echo "支付成功,正在跳转";
    }
}
