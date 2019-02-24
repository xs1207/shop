@extends('layouts.bst');

@section('content')
    <div class="container">
        <h3>客服私聊</h3>

        <h2>开聊... openid:{{$openid}}</h2>

        <div class="chat" id="chat_div">

        </div>
        <hr>

        <form action="" class="form-inline">
            <input type="hidden" value="{{$openid}}" id="openid">
            <input type="hidden" value="1" id="msg_pos">
            <textarea name="" id="send_msg" cols="100" rows="5"></textarea>
            <button class="btn btn-info" id="send_msg_btn">Send</button>
        </form>




        {{--<form class="form-inline">--}}

            {{--<span> <font size="4px"><b>聊天记录</b></font></span>--}}

            {{--<div>--}}
                {{--<div class="input-group">--}}
                    {{--<textarea name="content" class="form-control" cols="50" rows="10" id="area"></textarea>--}}
                {{--</div>--}}

            {{--</div>--}}
            {{--<br>--}}
            {{--<input type="text" class="form-control" id="msg">--}}
            {{--<button type="submit" class="btn btn-primary" id="btn1">Send Msg</button>--}}
        {{--</form>--}}
    </div>
@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/weixin/pchat.js')}}"></script>
@endsection