@extends('layouts.bst')

@section('content')
    <h2 class="form-signin-heading" style="margin-left: 0px">订单列表</h2>
    <table class="table table-bordered">
        <thead>
        <td>订单id</td><td>订单号</td><td>添加时间</td><td>价格</td><td>操作</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['order_id']}}</td>
                <td>{{$v['order_sn']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>{{$v['order_amount'] / 100}}</td>
                <td>
                    <a href="/pay/ment/{{$v['order_id']}}" class="btn btn-info">去支付</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('footer')
    @parent
@endsection