@extends('layouts.bst')

@section('content')
    {{csrf_field()}}
    <table class="table table-bordered">
        <thead>
        <td></td><td>ID</td><td>openid</td><td>add_time</td><td>nickname</td><td>sex</td><td>操作</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td><input type="checkbox" ></td>
                <td>{{$v['id']}}</td>
                <td>{{$v['openid']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>{{$v['nickname']}}</td>
                <td>
                    @if($v['sex']==1)
                        男
                    @elseif($v['sex']==2)
                        女
                    @endif
                </td>
                <td>
                    @if($v['black']==1)
                        <a href="/weixin/ks/black/{{$v['id']}}" class="btn btn-info">加入黑名单</a>
                        <a href="/weixin/ks/tags/" class="btn btn-info">打标签</a>
                    @elseif($v['black']==2)
                        <a href="">已入黑名单</a>
                    @endif
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