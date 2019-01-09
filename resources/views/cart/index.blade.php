@extends('layouts.bst')

@section('content')
    <h2 class="form-signin-heading" style="margin-left: 0px">购物车列表</h2>
    <table class="table table-bordered">
        <thead>
        <td>goods_name</td><td>num</td><td>add_time</td><td>操作</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['num']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>
                    <a href="/cart/del2/{{$v['goods_id']}}" class="btn btn-danger">删除</a>
                    <a href="/order/add/" id="submit_order" class="btn btn-info">提交订单</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('footer')
    @parent
@endsection