@extends('layouts.bst')

@section('content')

    <table class="table table-bordered">
        <thead>
        <td>UID</td><td>Name</td><td>Age</td><td>Email</td><td>Reg_time</td>
        </thead>
        <br>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['uid']}}</td><td>{{$v['name']}}</td><td>{{$v['age']}}</td><td>{{$v['email']}}</td><td>{{$v['reg_time']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
