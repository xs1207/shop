<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Document</title>
</head>
<body>
    <h2>表单测试</h2>
    <form action="/form/test" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="text" name="uname"></br></br></br>
        <input type="file" name="media"></br></br></br>
        <input type="submit" value="SUBMIT">
    </form>
</body>
</html>