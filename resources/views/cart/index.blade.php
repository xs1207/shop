@extends('layouts.bst')

@section('content')
    <h2 class="form-signin-heading" style="margin-left: 0px">购物车列表</h2>
    <table class="table table-bordered">
        <thead>
        <td>商品ID</td><td>商品名称</td><td>商品数量</td><td>添加时间</td><td>操作</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['goods_id']}}</td>
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['num']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>
                    <button class="btn btn-danger del" del_id="{{$v['goods_id']}}">删除</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="/order/add/" id="submit_order" class="btn btn-info" style="margin-left: 970px">提交订单</a>
@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/goods/goods.js')}}"></script>
@endsection