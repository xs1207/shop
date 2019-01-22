@extends('layouts.bst')

@section('content')
    <form action="/upload/pdf" method="post" enctype="multipart/form-data">
    {{csrf_field()}}

        <input type="file"  name="zhu">
        <input type="submit" value="上传文件">

    </form>
@endsection
