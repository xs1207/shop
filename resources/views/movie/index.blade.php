@extends('layouts.bst')

@section('content')

    @foreach($seat as $k=>$v)

        @if($v==1)
            <button class="btn-default btn-danger"> 座位{{ $k }} </button>  <br>
        @else
            <button class="btn-default btn-info"><a href="/movie/buy/{{$k}}">座位{{ $k }}</a>  </button>  <br>
        @endif


    @endforeach

@endsection