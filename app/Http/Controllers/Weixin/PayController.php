<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Weixin\WXBizDataCryptController;
use App\Model\OrderModel;
use App\Model\GoodsModel;


class PayController extends Controller
{
    //
    public $weixin_unifiedorder_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $weixin_notify_url = 'https://dkl.tactshan.com/weixin/pay/notice';     //支付通知回调

    public function test($order_id)
    {
        $res=OrderModel::where(['order_id'=>$order_id])->first();
        $total_fee = $res['order_amount'];         //用户要支付的总金额
//        print_r($total_fee);die;
        $order_info = [
            'appid'         =>  env('WEIXIN_APPID_0'),      //微信支付绑定的服务号的APPID
            'mch_id'        =>  env('WEIXIN_MCH_ID'),       // 商户ID
            'nonce_str'     => str_random(16),             // 随机字符串
            'sign_type'     => 'MD5',
            'body'          => '测试订单-'.mt_rand(1111,9999) . str_random(6),
            'out_trade_no'  => $res['order_sn'],                       //本地订单号
            'total_fee'     => $total_fee,
            'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],     //客户端IP
            'notify_url'    => $this->weixin_notify_url,        //通知回调地址
            'trade_type'    => 'NATIVE'                         // 交易类型
        ];


        $this->values = [];
        $this->values = $order_info;
        $this->SetSign();

        $xml = $this->ToXml();      //将数组转换为XML
        $rs = $this->postXmlCurl($xml, $this->weixin_unifiedorder_url, $useCert = false, $second = 30);

        $data =  simplexml_load_string($rs);
//        //var_dump($data);echo '<hr>';


        $data=[
            'order_id'=>$order_id,
            'code_url'=>$data->code_url
        ];
        return view('weixin.wxpay',$data);
        //echo '<pre>';print_r($data);echo '</pre>';

        //将 code_url 返回给前端，前端生成 支付二维码

    }

    protected function ToXml()
    {
        if(!is_array($this->values)
            || count($this->values)<=0)
        {
            die("数组数据出现异常");
        }
        $xml= "<xml>";
        foreach($this->values as $key=>$val)
        {
            if(is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    private function postXmlCurl($xml,$url,$useCert=false,$second=30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //POST提交方式
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        //运行curl
        $data = curl_exec($ch);
//        var_dump($data);die;
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            curl_close($ch);
            die("curl出错，错误码：$error");
        }
    }
    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }


    private function MakeSign()
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".env('WEIXIN_MCH_KEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }


    /**
     * 微信支付回调
     */
    public function notice()
    {
        $data = file_get_contents("php://input");

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_pay_notice.log',$log_str,FILE_APPEND);

        $xml = simplexml_load_string($data);

        if($xml->result_code=='SUCCESS' && $xml->return_code=='SUCCESS'){      //微信支付成功回调
            //验证签名
            $sign = true;
//            $sign=$this->SetSign();
//            $xml->sign==
            if($sign){       //签名验证成功
                //TODO 逻辑处理  订单状态更新
                $order_number=$xml->out_trade_no;
                $data=[
                    'is_pay'=>1,        //订单状态 0未支付  1 已支付
                    'pay_time'=>time()      //支付时间
                ];
                $where=[
                    'order_number'=>$order_number       //订单号
                ];
                $res=OrderModel::where($where)->update($data);
//修改库存
                $res1=OrderModel::where($where)->first();
                $goods_id=$res1['goods_id'];
                $num=$res1['goods_num'];
                $goods_id=explode(',',$goods_id);
                $num=explode(',',$num);
//print_r($goods_id);exit;
                foreach($goods_id as $k=>$v){
                    //echo $v;
                    $res2=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
                    foreach ($num as $val){
                        //echo $val;
                    }
                    $store=$res2['store']-$val;
                    if($store<=0){
                        exit('库存不足');
                    }
                    $data=[
                        'store'=>$store
                    ];
                    $res3=GoodsModel::where(['goods_id'=>$v])->update($data);
                    //print_r($data);
                }
            }else{
                //TODO 验签失败
                echo '验签失败，IP: '.$_SERVER['REMOTE_ADDR'];
                // TODO 记录日志
            }

        }

        $response = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $response;

    }

}














