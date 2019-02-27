@extends('layouts.bst')

@section('content')
    <form action="/goods/list" method="get">
    <input type="text" name="search"><input type="submit"  value="点击搜索">
    </form>
    <table class="table table-bordered">
        <thead>
        <td>商品ID</td><td>商品名称</td><td>库存</td><td>添加时间</td><td>操作</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['goods_id']}}</td>
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['store']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>
                    <a href="/goods/detail/{{$v['goods_id']}}" class="btn btn-info">详情</a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    {{ $list->links() }}
@endsection

@section('footer')
    @parent
@endsection