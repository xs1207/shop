@extends('layouts.bst')

@section('content')
    <h2 class="form-signin-heading" style="margin-left: 0px">订单列表</h2>
    <table class="table table-bordered">
        <thead align="center">
        <td>订单id</td><td>订单号</td><td>添加时间</td><td>价格</td><td>操作</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr align="center">
                <td>{{$v['order_id']}}</td>
                <td>{{$v['order_sn']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>￥{{$v['order_amount'] / 100}}</td>
                <td>
                    @if($v['is_pay']==0)
                    <a href="/pay/ment/{{$v['order_id']}}" class="btn btn-info" >支付宝支付</a>
                    <a href="/weixin/pay/test/{{$v['order_id']}}" class="btn btn-info" >微信支付</a>
                    <a href="#"class="btn btn-info" >取消订单</a>
                    @elseif($v['is_pay']==1)
                        <a href="#" class="btn btn-info" >已支付</a>
                        <a href="#" class="btn btn-info" >退款</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('footer')
    @parent
@endsection